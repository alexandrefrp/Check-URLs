<html>
<title>Monitora&ccedil;&atilde;o Sites - Log - <?=Date('d/m/Y H:i:s')?></title>
<meta http-equiv=Refresh content=60>
<link rel="shortcut icon" href="favicon.png">
<head>
	<link rel="stylesheet" type="text/css" href="home.css">
	<script language=javascript>
		function blink() {
		var blink = document.all.tags("BLINK")
		for (var i=0; i<blink.length; i++)
		blink[i].style.visibility = blink[i].style.visibility == "" ? "hidden" : ""
		}
		
		function startBlink() {
		if (document.all)
		setInterval("blink()",500)
		}
		window.onload = startBlink;
	</script>
	<style type="text/css"> <!-- Tooltip -->
			.formata { /* esta classe é somente para formatar a fonte */
				font: 12px Trebuchet MS;
			}
			a.dcontexto{
				position:relative;
				/* font:12px Trebuchet MS; */
				padding:0;
				color:black;
				text-decoration:none;
				border-bottom:2px /* dotted #039 */;
				cursor:help; 
				z-index:24;
			}
			a.dcontexto:hover{
				background:transparent;
				color:#f00;
				z-index:25;
			}
			a.dcontexto span{display: none}
			a.dcontexto:hover span{
				display:block;
				position:absolute;
				width:190px; 
				/* top:3em; */
				right-align:justify;
				right:250px;
				font: 12px Trebuchet MS;
				padding:5px 10px;
				border:1px solid #999;
				background:#DDD;
				color:#000;
			}
	</style>
</head>

<?php
//Captura parametros, pagina anterior
$id = $_REQUEST["id"];
$descricao = $_REQUEST["descricao"];
?>

<!-- Data: <font size=5 color=red><?=Date('H/m/Y')?></font> - <a href='#' alt='Ver histórico'>Logs</a> -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" align="center">
	<tr bgcolor="white">
        <td colspan=5 align="center"><font color="black"><b>Logs de erro: <?=$descricao?></b></font></td>
	</tr>
 
	<tr>
        <td align="center"><b>Data Inicio</b></td>
		<td align="center"><b>Data Fim</b></td>
		<td align="center"><b>Status</b></td>
		<td align="center"><b>Erro</b></td>
		<td align="center"><b>Contador</b></td>
	</tr>
<?php
/*
'-------------------------------------------------------------------------
'Versão 1.1 - Mon - Alexandre Pimentel
'Efetua testes nos sites configurados
'Gera alerta visual na página em caso de situações anormais para analise
'-------------------------------------------------------------------------
*/

$banco = "monitools";
$usuario = "USER";
$senha = "PASSWORD";
$hostname = "localhost";
$conn = mysql_connect($hostname,$usuario,$senha); mysql_select_db($banco) or die( "Não foi possível conectar ao banco MySQL");
if (!$conn) {echo "Não foi possível conectar ao banco MySQL.
"; exit;}

$sql = "SELECT * FROM sites_log
		WHERE id = $id
		ORDER BY data_ini DESC
		LIMIT 500";

$rs = @mysql_query($sql,$conn);

$cor_linha = "#dde4ef";
$contador_ok = 0;


//Exibe as linhas encontradas na consulta
while ($row = mysql_fetch_array($rs)) {

	$data_ini = $row["data_ini"];
	$data_fim = $row["data_fim"];
	$status = $row["status"];
	$erro = $row["erro"];
	$contador = $row["contador"];
	
	
	//Define cor da linha
	if ($cor_linha == "yellow"){
		$cor_linha = "blue";
		$cor_fonte = "black";
		
	}else{
		if ($cor_linha == "#dde4ef") {
			$cor_linha = "white";
		}else{
			$cor_linha = "#dde4ef";
		}
		$cor_fonte = "black";
		$contador_ok = $contador_ok + 1;
	}
	//echo $id;
    //echo "\n";
?>
	<tr bgcolor='<?=$cor_linha?>'>
        <td align="center"><font color="<?=$cor_fonte?>"><b><?=$data_ini?></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><?=$data_fim?></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><?=$status?></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><?=$erro?></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><?=$contador?></b></font></td>
	</tr>
<?php
    //$rs->MoveNext();
}

mysql_close(); 

$rs = null;
$conn = null;
?>

 </table>
</body>
</html>