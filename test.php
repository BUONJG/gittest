<?php
include "../../include/include.batch.php";

// Script de 
gbatch("Admin : Création dans la base admin des bases nécessaires");

$DBCible        = 'DataBaseCore';
$DBSource       = 'MySQL:ips_aa_configuration'; // DB_APPLI;
$TranslationTbl = Translate::TBL_MSG;

$tCodesLangue = array('en', 'fr', 'es');

foreach ($tCodesLangue AS $CodeLangue)
{
	////////////////////////////////////////////////////////////////////
	gbatch()->log("$CodeLangue - 1 - Recherche des codes non traduits");
	////////////////////////////////////////////////////////////////////
	// On récupère les infos à insérer
	$sqlSelect = "SELECT clef, module, code_message";
	$sqlSelect.= "  FROM $TranslationTbl";
	$sqlSelect.= " WHERE lang_{$CodeLangue} = '' OR lang_{$CodeLangue} is null";
	$hTransIds = gdb($DBCible)->execute($sqlSelect)->gethtbl('clef');
	$nbMsg = count($hTransIds);
	gbatch()->log("=> Reprise de $nbMsg Messages Ecran");

	//////////////////////////////////////////////////////////////////////////////
	gbatch()->log("$CodeLangue - 2 - Mise à Jour Via le code message uniquement");
	//////////////////////////////////////////////////////////////////////////////
	$tUpdate = array();
	foreach ($hTransIds AS $MsgId => $hMsg)
	{
		$Module      = $hMsg->getvalue('module'      , '');
		$CodeMessage = $hMsg->getvalue('code_message', '');

		// 1 - On recherche un correspondance code * module
		$SearchFormula = "CONCAT(module, '###', code_message)";
		$SearchValue   = "{$Module}###{$CodeMessage}";
		$Traduction = gdb($DBSource, $TranslationTbl)->getvalue($SearchFormula, $SearchValue, "lang_{$CodeLangue}", '');

		// 2 - On recherche via le code_message simplement
		if (!(strlen($Traduction) > 0))
		{
			$SearchFormula = 'code_message';
			$SearchValue   = $CodeMessage;
			$TraductionRGB2 = gdb($DBSourceJGB, $TranslationTbl)->getvalue($SearchFormula, $SearchValue, "lang_{$CodeLangue}", '');
		}

		// On programme la mise à jour si la traduction a été trouvée
		if (strlen($Traduction) > 0)
		{
			$tUpdate["{$Module}###{$CodeMessage}"] = array("lang_{$CodeLangue}" => $Traduction);
		} 
	}

	////////////////////////////////////////////////////////////////////
	gbatch()->log("$CodeLangue - 3 - Mise à jour des codes référencés");
	////////////////////////////////////////////////////////////////////
	gdb($DBCible, $TranslationTbl)->update($tUpdate, "CONCAT(module, '###', code_message)");
}

echo "test";

////////////////
gbatch()->end();
////////////////
?>