<?php
class SqlFuncProc {
	private $thisPath = "";
	private $db;
	public static function getInstance($connStr, $user, $pass) {
		static $instance = null;
		if (null === $instance) {
			$instance = new static($connStr, $user, $pass);
		}
		return $instance;
	}
	protected function __construct($connStr, $user, $pass) {
		$reflector      = new ReflectionClass('SqlFuncProc');
		$this->thisPath = dirname($reflector->getFileName()) . DIRECTORY_SEPARATOR;
		try {
			$this->db   = new PDO($connStr, $user, $pass);			
		} catch (Exception $e) {
			//print_r($e);
			return (false);
		}
		if (mb_internal_encoding() == 'ISO-8859-1' && preg_match('/(.*sqlsrv)/', strtolower($connStr))) {
			$this->db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
		} else {
			$this->db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		}
	}
	public function runFunc($proc, $params = array(), $limit = false) {
		$resArray = array();
		$i        = 0;
		$file     = $this->thisPath . 'SqlFunc' . DIRECTORY_SEPARATOR . $proc . '.sql';
		$sql      = file_get_contents($file);
		$query    = $this->db->prepare($sql);
		$query->execute($params);
		if ($query->errorCode() != '0000') {
			return $query->errorCode();
		}
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$resArray[] = $row;
			$i++;
			if ($limit != false && $limit == $i)
				return $resArray;
		}
		return $resArray;
	}
	public function runProc($proc, $params = array(), $force = false) {
		$file = $this->thisPath . 'SqlProc' . DIRECTORY_SEPARATOR . $proc . '.sql';
		$sql  = file_get_contents($file);
		if ($force) {
			$sql = $this->forcePrepare($sql, $params);
			$this->db->exec($sql);
			return $this->db->errorCode();
		} else {
			$query = $this->db->prepare($sql);
			$query->execute($params);
			return $query->errorInfo();
		}
	}
	private function forcePrepare($sql, $params = array()) {
		foreach ($params as $param) {
			if (strpos($sql, '?') !== false) {
				$sql = substr($sql, 0, strpos($sql, '?')) . "'" . $param . "'" . substr($sql, strpos($sql, '?') + 1);
			}
		}
		return $sql;
	}
	public function wievProc($name = '', $class="") {
		$SqlProc = $this->getFiles($this->thisPath . 'SqlProc' . DIRECTORY_SEPARATOR, $type = "sql");
		$table   = "<table class='".$class."'><caption>Sql Procedures</caption><thead><tr><td>File</td><td>SQL string</td></tr></thead><tbody>";
		$re      = '/(.*ab)/';
		foreach ($SqlProc as $value) {
			if ($name == '' || preg_match('/(.*' . strtolower($name) . ')/', strtolower($value))) {
				$file   = $this->thisPath . 'SqlProc' . DIRECTORY_SEPARATOR . $value . '.sql';
				$data   = file_get_contents($file);
				$table .= '<tr><td><br>' . $value . '</td><td>' . $data . '</td></tr>';
			}
		}
		return $table . '</tbody></table>';
	}
	public function wievFunc($name = '', $class="") {
		$SqlFunc = $this->getFiles($this->thisPath . 'SqlFunc' . DIRECTORY_SEPARATOR, $type = "sql");
		$table   = "<table class='".$class."'><caption>Sql Functions</caption><thead><tr><td>File</td><td>SQL string</td></tr></thead><tbody>";
		$re      = '/(.*ab)/';
		foreach ($SqlFunc as $value) {
			if ($name == '' || preg_match('/(.*' . strtolower($name) . ')/', strtolower($value))) {
				$file   = $this->thisPath . 'SqlFunc' . DIRECTORY_SEPARATOR . $value . '.sql';
				$data   = file_get_contents($file);
				$table .= '<tr><td><br>' . $value . '</td><td>' . $data . '</td></tr>';
			}
		}
		return $table . '</tbody></table>';
	}
	public function getHTMLtable($array = array(), $id = "", $class = "", $head = true) {
		$ret           = '';
		$firstrow      = true;
		$firstrow_data = '';
		foreach ($array as $row) {
			if ($firstrow == true) {
				$ret .= '<table id = "' . $id . '" class = "' . $class . '"><thead><tr>';
			} else {
				$ret .= '<tr>';
			}
			foreach ($row as $key => $value) {
				if ($firstrow == true && $head == true) {
					$ret .= '<th>' . $key . '</th>';
					$firstrow_data .= '<td>' . $value . '</td>';
				} else {
					$ret .= '<td>' . $value . '</td>';
				}
			}
			if ($firstrow == true) {
				$firstrow = false;
				$ret .= '</tr><tbody>';
				$ret .= '<tr>' . $firstrow_data . '</tr>';
			} else {
				$ret .= '</tr>';
			}
		}
		$ret .= '</tbody>';
		$ret .= '</table>';
		return $ret;
	}
	private function getFiles($openFolder, $type = "sql", $one = false) {
		$ipFiles = array();
		$i       = 0;
		if ($handle = opendir($openFolder)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && strtolower(substr($entry, strlen($entry) - 3)) == $type) {
					if ($one) {
						return $entry;
					}
					$splitted  = explode('.', $entry);
					$ipFiles[] = $splitted[0];
				}
			}
			closedir($handle);
		}
		sort($ipFiles);
		return $ipFiles;
	}
}