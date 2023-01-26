<?php
class Sign_documents_model extends CI_Model
{

	public function getAllCaptureRoleUsers()
	{
		$sql = "SELECT u.username, u.user_id
					FROM ci_users u 
					INNER JOIN ci_admin_roles r ON u.admin_role_id = r.admin_role_id
					WHERE r.admin_role_title = 'Capture'";

		$rs = $this->db->query($sql);
		return $rs->result();
	}

	public function updateFolder($folderId, $folderData)
	{
		return $this->db->update('ci_folders', $folderData, 'folder_id = ' . $folderId);
	}

	public function fileVersionConverted($fileId)
	{
		return $this->db->update('ci_files', array('is_converted' => 1), 'file_id = ' . $fileId);
	}

	public function setFolderCompleted($folderId)
	{
		return $this->db->update('ci_folders', array('is_completed' => 1), 'folder_id = ' . $folderId);
	}

	public function getMyUncompletedFolders($userId, $adminRole)
	{
		if ($adminRole == 'Client') {
			$rs = $this->db->select('folder_id, company_name, date_created')
				->order_by('date_created', 'DESC')
				->get('ci_folders');
		} else { //capture role
			$rs = $this->db->select('folder_id, company_name, date_created')
				->where(array('capture_role_id' => $userId, 'is_completed' => 0))
				->order_by('date_created', 'DESC')
				->get('ci_folders');
		}
		return $rs->result();
	}

	public function getMyCompletedFolders($userId, $adminRole)
	{
		if ($adminRole == 'Client') {
			$rs = $this->db->select('folder_id, company_name, date_created')
				->order_by('date_created', 'DESC')
				->get('ci_folders');
		} else { //capture role
			$rs = $this->db->select('folder_id, company_name, date_created')
				->where(array('capture_role_id' => $userId, 'is_completed' => 1))
				->order_by('date_created', 'DESC')
				->get('ci_folders');
		}
		return $rs->result();
	}

	public function getMyFilesById($folderId)
	{
		$rs = $this->db->select('*')
			->where('folder_id', $folderId)
			->order_by('created_at', 'DESC')
			->get('ci_files');
		return $rs->result();
	}

	public function getMyFileById($fileId)
	{
		$rs = $this->db->select('*')
			->where('file_id', $fileId)
			->get('ci_files');
		return $rs->result();
	}

	public function getFilesByFolderId($folderId)
	{
		$rsFiles = $this->db->select('file_id, file_name, file_path, is_converted')
			->where('folder_id', $folderId)
			->order_by('created_at', 'DESC')
			->get('ci_files');
		return $rsFiles->result();
	}

	public function getFolderById($folderId)
	{
		$rsFolder = $this->db->select('signature, signature_path')
			->where('folder_id', $folderId)
			->get('ci_folders');
		return $rsFolder->row();
	}

	public function deleteFiles($folderId)
	{
		$this->db->delete('ci_files', array('folder_id' => $folderId));
		return true;
	}

	public function deleteFile($fileId)
	{
		$this->db->delete('ci_files', array('file_id' => $fileId));
		return true;
	}

	public function deleteFolder($folderId)
	{
		$this->db->delete('ci_folders', array('folder_id' => $folderId));
		return true;
	}

	public function getFolderData($folderId)
	{
		$rsFiles = $this->db->select('*')
			->where('folder_id', $folderId)
			->get('ci_folders');
		return $rsFiles->row();
	}
}
