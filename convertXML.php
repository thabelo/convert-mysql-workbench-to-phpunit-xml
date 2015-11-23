<?php
/**
 * @author Thabelo Mmbengeni
 */

// get arguments 
$mpInputFile	= $argv[1];
$table_name		= $argv[2];

// if there are no arguments print usage information
if(count($argv) == 3){
	$XMLConverter = new XMLConverter();
	$oDom = $XMLConverter->convert($mpInputFile, $table_name);
	$XMLConverter->save($oDom);
}else{
	print "\n> Error: Inavlid input.\n> Usage\n>> File out : php convertWorkBenchToPHPUnitXML.php [filename] [tableName]  >> outputFile\n> Std out  : php convertWorkBenchToPHPUnitXML.php [filename] [tableName]  
		   \n";
}
/** 
 * Converts XML data to phpunit xml format
 */
class XMLConverter{
	private $oDom;
	private $dataSet;
	
	// temp variables
	private $sCurrentTable;
	private $oTmpTable;
	
	public function __construct(){
		// setup dom with root node
		$this->oDom = new DOMDocument('1.0');
		$this->dataSet = $this->oDom->createElement("dataset");
	}
	
	/**
	 * [convert description]
	 * @param  string $mpInputFile user input filename
	 * @param  string $table_name  user input table name
	 * @return DOMDocument object
	 */
	public function convert($mpInputFile, $table_name){
		$oTmpDom = new DOMDocument();
		$oTmpDom->load($mpInputFile);
		$oNodeList = $oTmpDom->getElementsByTagName('ROW');

		foreach($oNodeList as $oNode){
			// ignore table elements that are in the pma namespace
			if(strpos($oNode->nodeName, 'pma') === false){
				$this->tableData = $this->oDom->createElement($table_name);
				foreach ($oNode->getElementsByTagName("*") as $nodeItem) {
					$this->tableData->setAttribute($nodeItem->nodeName, $nodeItem->nodeValue); 
					$this->dataSet->appendChild($this->tableData);
				}
			}
		}
		$this->oDom->appendChild($this->dataSet);
		return $this->oDom;
	}
	/**
	 * send the xml string to stdout
	 * @return string
	 */
	public function save($oDom){
		return $oDom->save('php://stdout');
	}
}