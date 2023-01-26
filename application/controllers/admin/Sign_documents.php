<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sign_documents extends My_Controller
{

	public function __construct()
	{
		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();
		$this->load->model('admin/sign_documents_model', 'sign_documents_model');
	}


	public function view_file($filename)
	{
		$data['filename'] = $filename;
		$this->load->view('admin/documents/view_file', $data);
	}

	public function uncompleted()
	{
		$this->load->library('pagination');

		$config['base_url'] = base_url('admin/sign_documents/uncompleted');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tagl_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tagl_close'] = '</li>';
		$config['first_tag_open'] = '<li class="page-item disabled">';
		$config['first_tagl_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tagl_close'] = '</a></li>';
		$config['attributes'] = array('class' => 'page-link');
		$config['total_rows'] = 10;
		$config['per_page'] = 20;
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		// get_authors($config["per_page"], $page)
		// $this->db->limit($limit, $start);
		$data['title'] = 'List Uncompleted Documents';
		$data['pagi_links'] = $this->pagination->create_links();
		$this->load->view('admin/includes/_header', $data);
		$role = $this->session->userdata('admin_role');
		$currentUserId = $this->session->userdata('user_id');
		$data['userFolders'] = $this->sign_documents_model->getMyUncompletedFolders($currentUserId, $role);
		// echo '<Pre>';print_r($data['userFolders']);exit;
		$this->load->view('admin/documents/uncompleted_list', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function completed()
	{
		$data['title'] = 'List Completed Documents';
		$this->load->view('admin/includes/_header', $data);
		$role = $this->session->userdata('admin_role');
		$currentUserId = $this->session->userdata('user_id');
		$data['userFolders'] = $this->sign_documents_model->getMyCompletedFolders($currentUserId, $role);
		$this->load->view('admin/documents/completed_list', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function sign_folder($folderId)
	{
		//https://askubuntu.com/questions/113544/how-can-i-reduce-the-file-size-of-a-scanned-pdf-file
		//https://infoconic.com/blog/trick-for-fpdi-pdf-parser-that-supports-pdf-version-above-1-4/
		$folderDetails = $this->sign_documents_model->getFolderById($this->functions->decryptValue($folderId));
		if (empty($folderDetails->signature)) {
			$data = array(
				'errors' => '<p>Signature not found.</p>'
			);
			$this->setDefaulsMessages($data['errors']);
			redirect(base_url('admin/sign_documents/uncompleted'), 'refresh');
		}

		$allFiles = $this->sign_documents_model->getMyFilesById($this->functions->decryptValue($folderId));
		if ($allFiles and sizeof($allFiles) > 0) {

			require_once(APPPATH . 'third_party/fpdf/fpdf.php');
			require_once(APPPATH . 'third_party/FPDI/vendor/autoload.php');

			$signatureCompletedPath = FCPATH . 'assets/signatures_completed/';
			$pdfPath = FCPATH . 'assets/pdf_uploads/';
			$signaturePath = 'assets/signatures/';
			// gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=file_new.pdf file.pdf
			// gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=file.pdf newfile.pdf 
			// https://www.drupal.org/project/views_pdf/issues/2933498
			foreach ($allFiles as $key => $file) {
				if (empty($folderDetails->signature) || empty($file->file_name)) {
					continue;
				}

				$pdfversion = 0;
				$filepdf = fopen($pdfPath . $file->file_name, "r");
				if ($filepdf) {
					$firstLine = fgets($filepdf);
					preg_match_all('!\d+!', $firstLine, $matches);
					$pdfversion = implode('.', $matches[0]);
					fclose($filepdf);
				} else {
					die("error opening the file.");
				}

				$fileName = $file->file_name;
				if ($pdfversion > '1.4') {
					$fileName = 'dg-' . $file->file_name;
					shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $pdfPath . $fileName . '" "' . $pdfPath . $file->file_name . '"');
					$this->sign_documents_model->fileVersionConverted($file->file_id);
				}

				$pdf = new setasign\Fpdi\Fpdi();
				$pdf->AddPage();
				$pageCount = $pdf->setSourceFile($pdfPath . $fileName);
				if (!empty($pageCount) && $pageCount > 0) {
					for ($i = 1; $i < ($pageCount + 1); $i++) {
						$tplIdx = $pdf->importPage($i);

						$pageSize = $pdf->getTemplateSize($tplIdx);
						if ($i > 1) { //ignore white page on first iterate
							if ($pageSize[0] > $pageSize[1]) {
								$pdf->AddPage('L', array($pageSize[0], $pageSize[1]));
							} else {
								$pdf->AddPage('P', array($pageSize[0], $pageSize[1]));
							}
						}
						$pdf->useTemplate($tplIdx, 0, 0, null, null, true);
						$propotionUnit = 0.065;
						list($posX, $posY) = explode('x', $file->signature_point);
						list($width, $height) = getimagesize($signaturePath . $folderDetails->signature);

						$pdf->Image($signaturePath . $folderDetails->signature, $posX, $posY, $width * $propotionUnit, $height * $propotionUnit);
					}
				}

				$pdf->Output('F', $signatureCompletedPath . $file->file_name);
				// exit;
			}

			$this->sign_documents_model->setFolderCompleted($this->functions->decryptValue($folderId));
			$this->session->set_flashdata('success', 'Folder has been signed successfully!');
			redirect(base_url('admin/sign_documents/uncompleted'), 'refresh');
		};
	}
	public function list_files($folderId)
	{
		$data['title'] = 'List PDF Files';
		$this->load->view('admin/includes/_header', $data);
		$data['pdfFiles'] = $this->sign_documents_model->getMyFilesById($folderId / 786);
		$data['folderId'] = $folderId;
		$this->load->view('admin/documents/list_files_capture', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function list_completed_files($folderId)
	{
		$data['title'] = 'List PDF Files';
		$this->load->view('admin/includes/_header', $data);
		$data['pdfFiles'] = $this->sign_documents_model->getMyFilesById($folderId / 786);
		$data['folderId'] = $folderId;
		$this->load->view('admin/documents/list_completed_files', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function delete_file($fileId)
	{
		$fileData = $this->sign_documents_model->getMyFileById($fileId / 786);
		if (!empty($fileData[0]->file_name)) {
			$this->functions->delete_file('assets/pdf_uploads/' . $fileData[0]->file_name);
			$this->functions->delete_file('assets/signatures_completed/' . $fileData[0]->file_name);
			if ($fileData[0]->is_converted == 1) {
				$this->functions->delete_file('assets/pdf_uploads/' . 'dg-' . $fileData[0]->file_name);
			}
		}
		$this->sign_documents_model->deleteFile($fileData[0]->file_id);
		$msg = 'File has been deleted successfully!';
		$this->session->set_flashdata('success', $msg);
		redirect(base_url('admin/sign_documents/completed/' . $this->functions->encryptValue($fileData[0]->folder_id)), 'refresh');
	}

	public function delete_folder($folderId)
	{
		$folderId = intval($folderId / 786);
		$allFiles = $this->sign_documents_model->getFilesByFolderId($folderId);

		if (!empty($allFiles) && sizeof($allFiles) > 0) {
			foreach ($allFiles as $i => $file) {
				if (!empty($file->file_name)) {
					$this->functions->delete_file('assets/pdf_uploads/' . $file->file_name);
					$this->functions->delete_file('assets/signatures_completed/' . $file->file_name);
					if ($file->is_converted == 1) {
						$this->functions->delete_file('assets/pdf_uploads/' . 'dg-' . $file->file_name);
					}
				}
			}
			$this->sign_documents_model->deleteFiles($folderId);
		}

		$folderObj = $this->sign_documents_model->getFolderById($folderId);
		if (!empty($folderObj)) {
			if (!empty($folderObj->signature)) {
				$this->functions->delete_file('assets/signatures/' . $folderObj->signature);
			}
			$this->sign_documents_model->deleteFolder($folderId);
		}

		$this->session->set_flashdata('success', 'Folder has been deleted successfully!');
		redirect(base_url('admin/sign_documents/uncompleted'), 'refresh');
	}

	public function update_folder($folderId)
	{

		$folderData = $this->sign_documents_model->getFolderData(intval($folderId / 786));

		if ($this->input->method() == 'post') {
			if ($this->input->post('submit')) {
				// upload Signature
				if (!empty($this->input->post('signature_dataurl'))) { // upload esignature
					$path = "assets/signatures/";
					$oldSignatureFile = $folderData->signature;
					$result = $this->functions->upload_image_dataURL($path, $this->input->post('signature_dataurl'));
					if ($result['status'] == 1) {
						if (!empty($oldSignatureFile)) {
							$this->functions->delete_file('assets/signatures/' . $oldSignatureFile);
						}
						$signatureData = array(
							'signatureFileName' => $result['data']['file_name'],
							'signatureFilePath' => $result['data']['file_path'],
						);
					} else {
						$data = array(
							'errors' => '<p>' . $result['msg'] . '</p>'
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/sign_documents'), 'refresh');
					}
				}

				if (!empty($_FILES['signature']['name'])) { // upload signatue file
					$path = "assets/signatures/";
					$oldSignatureFile = $folderData->signature;
					$result = $this->functions->file_insert($path, 'signature', 'image', '52428800');
					if ($result['status'] == 1) {
						if (!empty($oldSignatureFile)) {
							$this->functions->delete_file('assets/signatures/' . $oldSignatureFile);
						}
						$signatureData = array(
							'signatureFileName' => $result['msg'],
							'signatureFilePath' => FCPATH . $path . $result['msg']
						);
					} else {
						$data = array(
							'errors' => '<p>' . $result['msg'] . '</p>'
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/sign_documents/update_folder/' . $folderId), 'refresh');
					}
				}

				// create folder entry in database
				$folderData = array(
					'signature' => $folderData->signature,
					'signature_path' => $folderData->signature_path
				);

				if (!empty($signatureData['signatureFileName'])) {
					$folderData['signature'] = $signatureData['signatureFileName'];
					$folderData['signature_path'] = $signatureData['signatureFilePath'];
				}

				$updated = $this->sign_documents_model->updateFolder(intval($folderId / 786), $folderData);

				if ($updated) {
					$this->session->set_flashdata('success', 'Folder has been updated successfully!');
					redirect(base_url('admin/sign_documents/update_folder/' . $folderId), 'refresh');
				}
			}
		}


		$data['title'] = 'Add Signature';
		$this->load->view('admin/includes/_header', $data);
		$data['records'] = $this->sign_documents_model->getAllCaptureRoleUsers();
		$data['record'] = $folderData;
		$this->load->view('admin/documents/add_signature', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function setDefaulsMessages($errors = array(), $params = array())
	{
		if (!empty($errors)) {
			$this->session->set_flashdata('errors', $errors);
		}

		if (!empty($params)) {
			$this->session->set_flashdata('company_name', $params->post('company_name'));
			$this->session->set_flashdata('address1', $params->post('address1'));
			$this->session->set_flashdata('email', $params->post('email'));
			$this->session->set_flashdata('mobile_no', $params->post('mobile_no'));
			$this->session->set_flashdata('capture_role', $params->post('capture_role'));
			$this->session->set_flashdata('signature_dataurl', $params->post('signature_dataurl'));
		}
	}
}
