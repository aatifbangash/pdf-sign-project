<?php defined('BASEPATH') or exit('No direct script access allowed');

class Upload_documents extends My_Controller
{

	public function __construct()
	{
		parent::__construct();
		auth_check(); // check login auth
		$this->rbac->check_module_access();
		$this->load->model('admin/upload_documents_model', 'upload_documents_model');
	}

	public function index()
	{

		$data['title'] = 'Upload Documents';
		$this->load->view('admin/includes/_header', $data);
		$data['records'] = $this->upload_documents_model->getAllCaptureRoleUsers();
		$this->load->view('admin/documents/upload', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function view_file($fileId)
	{

		$fileObj = $this->upload_documents_model->getFileById($fileId);

		$signatureFileName = $this->upload_documents_model->getFolderSignature($fileObj->folder_id);
		$data['filename'] = $fileObj->file_name;
		$data['signatureFileName'] = $signatureFileName->signature;
		$data['fileObj'] = $fileObj;
		$this->load->view('admin/documents/view_file', $data);
	}

	public function update_signature_point($fileId)
	{
		$newPos = round($this->input->post('posX') / 2.83) . 'x' . round($this->input->post('posY') / 2.83);
		return $this->upload_documents_model->updateFile($fileId, array('signature_point' => $newPos));
	}
	public function upload()
	{
		if ($this->input->post('submit')) {
			// validation start
			$this->form_validation->set_rules('company_name', 'Company name', 'trim|required');
			$this->form_validation->set_rules('capture_role', 'Capture Role', 'trim|required');
			if (empty($_FILES['pdf_files']['name'][0])) {
				$this->form_validation->set_rules('pdf_files[]', 'PDF Files', 'required');
			}
			// if (empty($_FILES['signature']['name']) && empty($this->input->post('signature_dataurl'))){
			// $this->form_validation->set_rules('signature', 'Signature', 'trim|required');
			// }
			// validation end

			if ($this->form_validation->run() == FALSE) {
				$data = array(
					'errors' => validation_errors()
				);

				$this->setDefaulsMessages($data['errors'], $this->input);
				redirect(base_url('admin/upload_documents'), 'refresh');
			} else {

				// validate pdf file type and size before upoad
				$validateMultipleFiles = $this->functions->validateFiles($_FILES['pdf_files']);
				if ($validateMultipleFiles['status'] == 0) {
					$data = array(
						'errors' => '<p>' . $validateMultipleFiles['msg'] . '</p>'
					);

					$this->setDefaulsMessages($data['errors'], $this->input);
					redirect(base_url('admin/upload_documents'), 'refresh');
				}
				// validate pdf file type and size before upoad end

				// upload Signature
				$signatureData = array(
					'signatureFileName' => '',
					'signatureFilePath' => ''
				);
				if (!empty($this->input->post('signature_dataurl'))) { // upload esignature
					$path = "assets/signatures/";
					$result = $this->functions->upload_image_dataURL($path, $this->input->post('signature_dataurl'));
					if ($result['status'] == 1) {
						$signatureData = array(
							'signatureFileName' => $result['data']['file_name'],
							'signatureFilePath' => $result['data']['file_path'],
						);
					} else {
						$data = array(
							'errors' => '<p>' . $result['msg'] . '</p>'
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/upload_documents'), 'refresh');
					}
				}

				if (!empty($_FILES['signature']['name'])) { // upload signature file
					$path = "assets/signatures/";
					$result = $this->functions->file_insert($path, 'signature', 'image', '52428800');
					if ($result['status'] == 1) {
						$signatureData = array(
							'signatureFileName' => $result['msg'],
							'signatureFilePath' => FCPATH . $path . $result['msg']
						);
					} else {
						$data = array(
							'errors' => '<p>' . $result['msg'] . '</p>'
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/upload_documents'), 'refresh');
					}
				}

				// upload pdf files
				if (!empty($_FILES['pdf_files']['name']) && sizeof($_FILES['pdf_files']['name']) > 0) {
					$totalFilesUploaded = $this->functions->multiple_upload($_FILES['pdf_files']);
					if (!empty($totalFilesUploaded['error'])) {
						$errorList = '';
						foreach ($totalFilesUploaded as $index => $error) {
							$errorList .= '<p>' . $error . '</p>';
						}
						$data = array(
							'errors' => $errorList
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/upload_documents'), 'refresh');
					} else {

						if (!empty($totalFilesUploaded) && sizeof($totalFilesUploaded) > 0) {

							// create folder entry in database
							$folderData = array(
								'company_name' => $this->input->post('company_name'),
								'folder_name' => url_title($this->input->post('company_name'), 'dash', true) . '-' . time(),
								'added_by_id' => $this->session->userdata('user_id'),
								'address' => $this->input->post('address1'),
								'email' => $this->input->post('email'),
								'mobile_no' => $this->input->post('mobile_no'),
								'capture_role_id' => $this->input->post('capture_role'),
								'signature' => $signatureData['signatureFileName'],
								'signature_path' => $signatureData['signatureFilePath'],
								'date_created' => date('Y-m-d H:i:s')
							);
							$folderId = $this->upload_documents_model->createFolder($folderData);

							// create files entry in database
							foreach ($totalFilesUploaded as $i => $fileEntry) {
								list($pdfWidth, $pdfHeight) = $this->pdfWidthHeight($fileEntry['full_path']);
								$data = array(
									'file_name' => $fileEntry['file_name'],
									'file_path' => $fileEntry['full_path'],
									'file_width' => $pdfWidth,
									'file_height' => $pdfHeight,
									'folder_id' => $folderId,
									'browser' => $this->agent->browser(),
									'browser_version' => $this->agent->version(),
									'os' => $this->agent->platform(),
									'ip_address' => $this->input->ip_address(),
									'created_at' => date('Y-m-d H:i:s'),
									'updated_at' => date('Y-m-d H:i:s'),
								);
								$data = $this->security->xss_clean($data);
								$result = $this->upload_documents_model->createFile($data);
							}

							if ($result) {
								$this->session->set_flashdata('success', 'Files have been added successfully!');
								redirect(base_url('admin/upload_documents/list'), 'refresh');
							}
						}
					}
				}
			}
		} else {
			redirect(base_url('admin/upload_documents'), 'refresh');
		}
	}

	public function pdfWidthHeight($pdfPath)
	{
		$output = shell_exec("pdfinfo $pdfPath");

		// find page count
		// preg_match('/Pages:\s+([0-9]+)/', $output, $pagecountmatches);
		// $pagecount = $pagecountmatches[1];

		// find page sizes
		preg_match('/Page size:\s+([0-9]{0,5}\.?[0-9]{0,3}) x ([0-9]{0,5}\.?[0-9]{0,3})/', $output, $pagesizematches);
		$width = round($pagesizematches[1]);
		$height = round($pagesizematches[2]);
		return array($width, $height);
	}
	public function list()
	{
		$data['title'] = 'List Documents';
		$this->load->view('admin/includes/_header', $data);
		$adminRole = $this->session->userdata('admin_role');
		$currentUserId = $this->session->userdata('user_id');
		$data['userFolders'] = $this->upload_documents_model->getMyFolders($currentUserId, $adminRole);
		$data['adminRole'] = $adminRole;
		$this->load->view('admin/documents/list', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function completed_documents()
	{
		$data['title'] = 'List Documents';
		$this->load->view('admin/includes/_header', $data);
		$adminRole = $this->session->userdata('admin_role');
		$currentUserId = $this->session->userdata('user_id');
		$data['userFolders'] = $this->upload_documents_model->getMyCompletedFolders($currentUserId, $adminRole);
		$this->load->view('admin/documents/completed_documents_list', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function list_files($folderId)
	{
		$data['title'] = 'List PDF Files';
		$this->load->view('admin/includes/_header', $data);
		$data['pdfFiles'] = $this->upload_documents_model->getMyFilesById($folderId / 786);
		$data['folderId'] = $folderId;
		$this->load->view('admin/documents/list_files', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function delete_file($fileId)
	{
		$fileData = $this->upload_documents_model->getMyFileById($fileId / 786);
		$msg = 'File has not been deleted!';
		if (!empty($fileData[0]->file_id)) {
			if (!empty($fileData[0]->file_name)) {
				$this->functions->delete_file('assets/pdf_uploads/' . $fileData[0]->file_name);
				if ($fileData[0]->is_converted == 1) {
					$result = $this->functions->delete_file('assets/pdf_uploads/' . 'dg-' . $fileData[0]->file_name);
				}
			}
			$this->upload_documents_model->deleteFile($fileData[0]->file_id);
			$msg = 'File has been deleted successfully!';
		}
		$this->session->set_flashdata('success', $msg);
		redirect(base_url('admin/upload_documents/list_files/' . $this->functions->encryptValue($fileData[0]->folder_id)), 'refresh');
	}

	public function delete_folder($folderId)
	{
		$folderId = intval($folderId / 786);
		$allFiles = $this->upload_documents_model->getFilesByFolderId($folderId);
		if (!empty($allFiles) && sizeof($allFiles) > 0) {
			foreach ($allFiles as $i => $file) {
				if (!empty($file->file_name)) {
					$result = $this->functions->delete_file('assets/pdf_uploads/' . $file->file_name);
					if ($file->is_converted == 1) {
						$result = $this->functions->delete_file('assets/pdf_uploads/' . 'dg-' . $file->file_name);
					}
				}
			}
			$this->upload_documents_model->deleteFiles($folderId);
		}

		$folderObj = $this->upload_documents_model->getFolderById($folderId);
		if (!empty($folderObj)) {
			if (!empty($folderObj->signature)) {
				$this->functions->delete_file('assets/signatures/' . $folderObj->signature);
			}
			$this->upload_documents_model->deleteFolder($folderId);
		}

		$this->session->set_flashdata('success', 'Folder has been deleted successfully!');
		redirect(base_url('admin/upload_documents/list'), 'refresh');
	}

	public function add_files($folderId)
	{
		if ($this->input->method() == 'post') {
			if ($this->input->post('submit')) {
				// validation start
				if (empty($_FILES['pdf_files']['name'][0])) {
					$this->form_validation->set_rules('pdf_files[]', 'PDF Files', 'required');
				}
				// validation end

				if (empty($_FILES['pdf_files']['name'][0]) && $this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);

					$this->setDefaulsMessages($data['errors'], $this->input);
					redirect(base_url('admin/upload_documents/add_files/' . $folderId), 'refresh');
				} else {

					// validate pdf file type and size before upoad
					$validateMultipleFiles = $this->functions->validateFiles($_FILES['pdf_files']);
					if ($validateMultipleFiles['status'] == 0) {
						$data = array(
							'errors' => '<p>' . $validateMultipleFiles['msg'] . '</p>'
						);

						$this->setDefaulsMessages($data['errors'], $this->input);
						redirect(base_url('admin/upload_documents/add_files/' . $folderId), 'refresh');
					}
					// validate pdf file type and size before upoad end

					// upload pdf files
					if (!empty($_FILES['pdf_files']['name']) && sizeof($_FILES['pdf_files']['name']) > 0) {
						$totalFilesUploaded = $this->functions->multiple_upload($_FILES['pdf_files']);
						if (!empty($totalFilesUploaded['error'])) {
							$errorList = '';
							foreach ($totalFilesUploaded as $index => $error) {
								$errorList .= '<p>' . $error . '</p>';
							}
							$data = array(
								'errors' => $errorList
							);

							$this->setDefaulsMessages($data['errors'], $this->input);
							redirect(base_url('admin/upload_documents/add_files/' . $folderId), 'refresh');
						} else {
							if (!empty($totalFilesUploaded) && sizeof($totalFilesUploaded) > 0) {
								// create files entry in database
								foreach ($totalFilesUploaded as $i => $fileEntry) {
									$data = array(
										'file_name' => $fileEntry['file_name'],
										'file_path' => $fileEntry['full_path'],
										'folder_id' => $this->functions->decryptValue($folderId),
										'browser' => $this->agent->browser(),
										'browser_version' => $this->agent->version(),
										'os' => $this->agent->platform(),
										'ip_address' => $this->input->ip_address(),
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s'),
									);
									$data = $this->security->xss_clean($data);
									$result = $this->upload_documents_model->createFile($data);
								}

								if ($result) {
									$this->session->set_flashdata('success', 'Files have been added successfully!');
									redirect(base_url('admin/upload_documents/list_files/' . $folderId), 'refresh');
								}
							}
						}
					}
					// create folder entry in database
					$folderData = array(
						'company_name' => $this->input->post('company_name'),
						// 'folder_name' => url_title($this->input->post('company_name'), 'dash', true) . '-' . time(),
						'address' => $this->input->post('address1'),
						'email' => $this->input->post('email'),
						'mobile_no' => $this->input->post('mobile_no'),
						'capture_role_id' => $this->input->post('capture_role')
					);

					if (!empty($signatureData['signatureFileName'])) {
						$folderData['signature'] = $signatureData['signatureFileName'];
						$folderData['signature_path'] = $signatureData['signatureFilePath'];
					}
					$updated = $this->upload_documents_model->updateFolder(intval($folderId / 786), $folderData);

					if ($updated) {
						$this->session->set_flashdata('success', 'Folder has been updated successfully!');
						redirect(base_url('admin/upload_documents/update_folder/' . $folderId), 'refresh');
					}
				}
			}
		}


		$data['title'] = 'Add files';
		$this->load->view('admin/includes/_header', $data);
		$data['folderId'] = $folderId;
		$this->load->view('admin/documents/add_files', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function update_folder($folderId)
	{

		$folderData = $this->upload_documents_model->getFolderData(intval($folderId / 786));

		if ($this->input->method() == 'post') {
			if ($this->input->post('submit')) {
				// validation start
				$this->form_validation->set_rules('company_name', 'Company name', 'trim|required');
				$this->form_validation->set_rules('capture_role', 'Capture Role', 'trim|required');
				// validation end

				if ($this->form_validation->run() == FALSE) {
					$data = array(
						'errors' => validation_errors()
					);

					$this->setDefaulsMessages($data['errors'], $this->input);
					redirect(base_url('admin/upload_documents/update_folder/' . $folderId), 'refresh');
				} else {
					// upload Signature
					if (!empty($this->input->post('signature_dataurl'))) { // upload esignature
						$path = "assets/signatures/";
						$oldSignatureFile = $folderData->signature;
						$result = $this->functions->upload_image_dataURL($path, $this->input->post('signature_dataurl'));
						if ($result['status'] == 1) {
							$this->functions->delete_file('assets/signatures/' . $oldSignatureFile);
							$signatureData = array(
								'signatureFileName' => $result['data']['file_name'],
								'signatureFilePath' => $result['data']['file_path'],
							);
						} else {
							$data = array(
								'errors' => '<p>' . $result['msg'] . '</p>'
							);

							$this->setDefaulsMessages($data['errors'], $this->input);
							redirect(base_url('admin/upload_documents'), 'refresh');
						}
					}

					if (!empty($_FILES['signature']['name'])) { // upload signatue file
						$path = "assets/signatures/";
						$oldSignatureFile = $folderData->signature;
						$result = $this->functions->file_insert($path, 'signature', 'image', '52428800');
						if ($result['status'] == 1) {
							$this->functions->delete_file('assets/signatures/' . $oldSignatureFile);
							$signatureData = array(
								'signatureFileName' => $result['msg'],
								'signatureFilePath' => FCPATH . $path . $result['msg']
							);
						} else {
							$data = array(
								'errors' => '<p>' . $result['msg'] . '</p>'
							);

							$this->setDefaulsMessages($data['errors'], $this->input);
							redirect(base_url('admin/upload_documents/update_folder/' . $folderId), 'refresh');
						}
					}

					// create folder entry in database
					$folderData = array(
						'company_name' => $this->input->post('company_name'),
						// 'folder_name' => url_title($this->input->post('company_name'), 'dash', true) . '-' . time(),
						'address' => $this->input->post('address1'),
						'email' => $this->input->post('email'),
						'mobile_no' => $this->input->post('mobile_no'),
						'capture_role_id' => $this->input->post('capture_role')
					);

					if (!empty($signatureData['signatureFileName'])) {
						$folderData['signature'] = $signatureData['signatureFileName'];
						$folderData['signature_path'] = $signatureData['signatureFilePath'];
					}
					$updated = $this->upload_documents_model->updateFolder(intval($folderId / 786), $folderData);

					if ($updated) {
						$this->session->set_flashdata('success', 'Folder has been updated successfully!');
						redirect(base_url('admin/upload_documents/update_folder/' . $folderId), 'refresh');
					}
				}
			}
		}


		$data['title'] = 'Update Documents';
		$this->load->view('admin/includes/_header', $data);
		$data['records'] = $this->upload_documents_model->getAllCaptureRoleUsers();
		$data['record'] = $folderData;
		$this->load->view('admin/documents/update', $data);
		$this->load->view('admin/includes/_footer');
	}

	public function setDefaulsMessages($errors, $params)
	{
		$this->session->set_flashdata('errors', $errors);
		$this->session->set_flashdata('company_name', $params->post('company_name'));
		$this->session->set_flashdata('address1', $params->post('address1'));
		$this->session->set_flashdata('email', $params->post('email'));
		$this->session->set_flashdata('mobile_no', $params->post('mobile_no'));
		$this->session->set_flashdata('capture_role', $params->post('capture_role'));
		$this->session->set_flashdata('signature_dataurl', $params->post('signature_dataurl'));
	}
}
