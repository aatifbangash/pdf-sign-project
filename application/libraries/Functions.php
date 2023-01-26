<?php
	class Functions 
	{
		function __construct()
		{
			$this->obj =& get_instance(); 
		}

		//--------------------------------------------------------
		function encode($input) 
		{
			return urlencode(base64_encode($input));
		}

		//--------------------------------------------------------
		function decode($input) 
		{
			return base64_decode(urldecode($input) );
		}

		//--------------------------------------------------------
		// Paginaiton function 
		public function pagination_config($url,$count,$perpage) 
		{
			$config = array();
			$config["base_url"] = $url;
			$config["total_rows"] = $count;
			$config["per_page"] = $perpage;
			$config['full_tag_open'] = '<ul class="pagination pagination-split">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';

			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
			return $config;
		}
		
		public function validateFiles($handle)
		{
			$number_of_files_uploaded = count($handle['name']);
			for ($i = 0; $i < $number_of_files_uploaded; $i++) :
				$filename = $handle['name'][$i];
				$file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));	
				$filesize = $handle['size'][$i];
				$size = 200 * 1024 * 1024; //200mb

				if($file_extension == 'pdf')
				{
					if ($filesize <= $size) 
					{
						$return['msg'] = 'Validation Successfull.';
						$return['status'] = 1;
					}
					else
					{
						$return['msg'] = 'Files must be smaller then 200 MB';
						$return['status'] = 0;
						break;
					}
				}
				else
				{
					$return['msg'] = 'Files Must Be In PDF Format';
					$return['status'] = 0;	
					break;
				}
				
			endfor;
			return $return;
		}

		/*
		* Code above omitted purposely 
		* In your HTML form, your input[type=file] must be named *userfile[]*
		*/

		/*
		* Uploads multiple files creating a queue to fake multiple upload calls to
		* $_FILE
		*/
		public function multiple_upload($handle)
		{
			
			$this->obj->load->library('upload');
			$final_files_data = array();
			$number_of_files_uploaded = count($handle['name']);

			// Faking upload calls to $_FILE
			for ($i = 0; $i < $number_of_files_uploaded; $i++) :
			$path = 'assets/pdf_uploads/';
			$filename = $handle['name'][$i];
			$filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
			$file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));	
			$_FILES['userfile']['name']     = $handle['name'][$i];
			$_FILES['userfile']['type']     = $handle['type'][$i];
			$_FILES['userfile']['tmp_name'] = $handle['tmp_name'][$i];
			$_FILES['userfile']['error']    = $handle['error'][$i];
			$_FILES['userfile']['size']     = $handle['size'][$i];

			
			$config = array(
				'file_name'     => url_title($filenameWithoutExt, 'dash', true). '-' . time() . '.' . $file_extension,
				'allowed_types' => 'pdf', //'jpg|jpeg|png|gif'
				'max_size'      => (200 * 1024 * 1024), //200MB
				'overwrite'     => FALSE,
				'upload_path'
					=> FCPATH . $path
			);

			/* real path to upload folder ALWAYS */
			if ( ! file_exists(FCPATH.$path))
			{
				$create = mkdir(FCPATH.$path,0777,TRUE);
				if ( ! $create) {
					return array('error' => 'unable to create upload directory');
				}
			}

			$this->obj->upload->initialize($config);

			if ( ! $this->obj->upload->do_upload()) :
				return array('error' => $this->obj->upload->display_errors());
				
			else :
				$final_files_data[] = $this->obj->upload->data();
			endif;
			endfor;
			return $final_files_data;
		}

		// --------------------------------------------------------------
		/*
		* Function Name : File Upload
		* Param1 : Location
		* Param2 : HTML File ControlName
		* Param3 : Extension
		* Param4 : Size Limit
		* Return : FileName
		*/
	   
		function upload_image_dataURL($path, $dataUrl)
		{
			$return = array();
			/* real path to upload folder ALWAYS */
			if ( ! file_exists(FCPATH.$path))
			{
				$create = mkdir(FCPATH.$path,0777,TRUE);
				if ( ! $create) {
					{
						$return['msg'] = 'unable to create upload directory';
						$return['status'] = 0;
					}
					return $return;
				}
			}

			$img = str_replace('data:image/png;base64,', '', $dataUrl);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$new_filename= $this->rename_image('-signature-' . date('Ymdhis') . '.png');
			$uploadedFilePath = FCPATH . $path . $new_filename;
			$success = file_put_contents($uploadedFilePath, $data);
			if($success) {
				{
					$return['msg'] = 'esignature file uploaded successfully.';
					$return['data'] = array(
						'file_name' => $new_filename,
						'file_path' => $uploadedFilePath
					);
					$return['status'] = 1;
				}
			} else {
				{
					$return['msg'] = 'unable to upload esignature file.';
					$return['status'] = 0;
				}
			}
			return $return; 
		}

		// --------------------------------------------------------------
		/*
		* Function Name : File Upload
		* Param1 : Location
		* Param2 : HTML File ControlName
		* Param3 : Extension
		* Param4 : Size Limit
		* Return : FileName
		*/
	   
		function file_insert($location, $controlname, $type, $size)
		{
			$return = array();
			$type = strtolower($type);
			if(isset($_FILES[$controlname]) && $_FILES[$controlname]['name'] != NULL)
	        {
				$filename = $_FILES[$controlname]['name'];
				$file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				$filesize = $_FILES[$controlname]["size"];	
						
				if($type == 'image')
				{
					if($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png')
					{
						if ($filesize <= $size) 
						{
							$return['msg'] = $this->file_upload($location, $controlname);
							$return['status'] = 1;
						}
						else
						{
							$size=$size/1024;
							$return['msg'] = 'File must be smaller then  '.$size.' KB';
							$return['status'] = 0;
						}
					}
					else
					{
						$return['msg'] = 'File Must Be In jpg,jpeg,png Format';
						$return['status'] = 0;
						
					}
				}
				elseif($type == 'pdf')
				{
					if($file_extension == 'pdf')
					{
						if ($filesize <= $size) 
						{
							$return['msg'] = $this->file_upload($location, $controlname);
							$return['status'] = 1;
						}
						else
						{
							$size = $size/1024;
							$return['msg'] = 'File must be smaller then  '.$size.' KB';
							$return['status'] = 0;
						}
					}
					else
					{
						$return['msg'] = 'File Must Be In PDF Format';
						$return['status'] = 0;	
					}
				}
				elseif($type == 'excel')
				{
					if( $file_extension == 'xlsx' || $file_extension == 'xls')
					{
						if ($filesize <= $size) 
						{
							$return['msg'] = $this->file_upload($location, $controlname);
							$return['status'] = 1;
							
						}
						else
						{
							$size = $size/1024;
							$return['msg'] = 'File must be smaller then  '.$size.' KB';
							$return['status'] = 0;
						}
					}
					else
					{
						$return['msg'] = 'File Must Be In Excel Format Only allow .xlsx and .xls extension';
						$return['status'] = 0;
					}
				}
				elseif($type == 'doc')
				{
					if( $file_extension == 'doc' || $file_extension == 'docx' || $file_extension == 'txt' || $file_extension == 'rtf')
					{
						if ($filesize <= $size) 
						{
							$return['msg'] = $this->file_upload($location, $controlname);
							$return['status'] = 1;
						}
						else
						{
							$size=$size/1024;
							$return['msg'] = 'File must be smaller then  '.$size.' KB';
							$return['status'] = 0;
						}
					}
					else
					{
						$return['msg'] = 'File Must Be In doc,docx,txt,rtf Format'; 
						$return['status'] = 0;		
					}
				}
				else
				{
					$return['msg'] = 'Not Allow other than image,pdf,excel,doc file..';
					$return['status'] = 0;	
				}

			}
	        else
	        {
	            $return['msg'] = '';
				$return['status'] = 1;
	        }
			return $return;
		}


		/*
		* Function Name : File Delete
		* Param1 : Location
		* Param2 : OLD Image Name
		*/
		
		public function delete_file($oldfile)
	    {		
			if($oldfile)
			{
				if(file_exists(FCPATH.$oldfile)) 
				{
					unlink(FCPATH.$oldfile);		
				}
			}
	    }
	

		//--------------------------------------------------------
		/*
		* Function Name : File Upload
		* Param1 : Location
		* Param2 : HTML File ControlName
		* Return : FileName
		*/
		function file_upload($location, $controlname)
		{
			if ( ! file_exists(FCPATH.$location))
			{
				$create = mkdir(FCPATH.$location,0777,TRUE);
				if ( ! $create)
					return '';
			}
	        
			$new_filename= $this->rename_image($_FILES[$controlname]['name']);
			if(move_uploaded_file(realpath($_FILES[$controlname]['tmp_name']),$location.$new_filename))
			{
				return $new_filename;
			}
			else
			{
				return '';
			}     
		}

		/*
		* Function Name : Rename Image
		* Param1 : FileName
		* Return : FileName
		*/
		public function rename_image($img_name)
	    {
	        $randString = md5(time().$img_name);
	        $fileName =$img_name;
	        $splitName = explode(".", $fileName);
	        $fileExt = end($splitName);
	        return strtolower($randString.'.'.$fileExt);
		}
		
		public function encryptValue($id) {
			return $id * 786;
		}

		public function decryptValue($id) {
			return $id / 786;
		}
   
	}


?>