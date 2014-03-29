<?php
/*
 * PrestaShop 1.3 to 1.5 Images Migrator 
 * 
 * copyright 2014 ILS Dariusz Palka 
 * www: ilssoftware.com 
 * email: biuro@ilssoftware.com 
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details. 
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software 
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

$imageExtensions = "(jpg|gif|png)";

if ($argc != 3) {
	print "Usage: $argv[0] [path to source img dir] [path to destination img dir]\n\n";
	exit ( 1 );
}

$srcBaseDir = $argv [1];
$destBaseDir = $argv [2];

// get all product image files
$prodImgDir = $srcBaseDir . "/p/";

$allSrcFiles = scandir ( $prodImgDir );

// find only product basic images
$imageFilenamePattern = "(\d+)-(\d+)\.$imageExtensions";
$imgSrcFiles = preg_grep ( "/$imageFilenamePattern/", $allSrcFiles );

foreach ( $imgSrcFiles as $file ) {
	// get image filename parts
	preg_match ( "/$imageFilenamePattern/", $file, $nameParts );
	
	$imageId = $nameParts [2];
	$imageExt = $nameParts [3];
	
	$imageIdDigits = str_split ( $imageId );
	$destDigitDir = "";
	foreach ( $imageIdDigits as $d ) {
		$destDigitDir .= "$d/";
	}
	
	$destDir = $destBaseDir . "/p/$destDigitDir";
	print "Copying file: $file to dest dir: $destDir\n";
	
	mkdir ( $destDir, 0777, true );
	
	$srcFilePath = $prodImgDir . "/$file";
	$destFilePath = $destDir . "/$imageId.$imageExt";
	
	if (! copy ( $srcFilePath, $destFilePath )) {
		echo "failed to copy $srcFilePath to $destFilePath\n";
	}
}

?>