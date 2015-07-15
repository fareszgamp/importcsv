<?php
class Listing 
{

	public function dirList($folder)
	{
		$mappa = glob($folder,GLOB_ONLYDIR);
		if(!empty($mappa))
		{
			foreach($mappa as $file){ 
					$dirs[] = $file;
			}
			
		}
		else $dirs = false;
		return $dirs;
	}
	
	public function fileList($folder)
	{
		$mappa = glob($folder);
		if(!empty($mappa))
		{
			foreach($mappa as $file){ 
				if(is_file($file) && basename($file)!="." && basename($file)!="..")
				{

					$filesize = filesize($file);
					$siz = filesize($file);
					if ($siz < 1024) $siz .= " bájt";
					else $siz = round($siz / 1024, 2) . " kB";
					$upTime = filemtime($file);
					$data[] = array("uptime"=>$upTime,"size"=> $siz,"file"=>$file);
				}
			}
		}
		else $data = false;
		return $data;
	}
	
	public function dirSize($folder)
	{
		$mappa = glob($folder);
		if(!empty($mappa))
		{
			foreach($mappa as $file){
				if(is_dir($file))
				{
					$siz = $this->dirSize($file.'*/');
					$fileSize = $fileSize+$siz;
				}
				else if(is_file($file))
				{
					$fileSize = $fileSize + filesize($file);
				}
			}
		}
		else $fileSize = false;
		return $fileSize;
	}
}
?>