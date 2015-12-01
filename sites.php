<html>
<title>Monitora&ccedil;&atilde;o Sites - <?=Date('d/m/Y H:i:s')?></title>
<meta http-equiv=Refresh content=30>
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

<!-- Data: <font size=5 color=red><?=Date('H/m/Y')?></font> - <a href='#' alt='Ver histórico'>Logs</a> -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" align="center">
	<tr>
        <td align="center"><b>Data</b></td>
		<td align="center"><b>Descrição</b></td>
		<td align="center"><b><a href="https://support.google.com/webmasters/answer/40132?hl=pt-BR" target="_new">Status/Logs</a></b></td>
		<td align="center"><b>Erro</b></td>
		<td align="center"><b>Falhas</b></td>
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
//else {echo "Parabéns!! A conexão ao banco de dados ocorreu normalmente!.
//";}
//mysql_close(); 

$sql = "SELECT * FROM sites where ativo = 1 order by descricao";

$rs = @mysql_query($sql,$conn);


//$rs = $conn->Execute("SELECT * FROM sites where ativo = 1 order by descricao");    // Recordset

/*
$num_columns = $rs->Fields->Count();
echo $num_columns . "\n";

for ($i=0; $i < $num_columns; $i++) {
    $fld[$i] = $rs->Fields($i);
}
*/
$cor_linha = "#dde4ef";
$contador_ok = 0;
$data_hoje = Date('Y-m-d');


//Exibe as linhas encontradas na consulta
while ($row = mysql_fetch_array($rs)) {
//while (!$rs->EOF) {
    //for ($i=0; $i < $num_columns; $i++) {
        //echo $fld[$i]->value . "\t";
    //}
	$id = $row["id"];
	$data = $row["data"];
	$status = $row["status"];
	$erro = $row["erro"];
	$descricao = $row["descricao"];
	$url = $row["url"];
	$info = $row["info"];
	
	
	//Define cor da linha
	if ((($status <> "200") and ($status <> "302")) or ($erro <> "0")){
		$cor_linha = "yellow";
		$cor_fonte = "black";
		$contador_ok = $contador_ok + 1;
		
		$erro_descricao = $erro;
		if ($erro <> "0") {
			$erro = "Ver erro";
		}
		//Verifica se Status esta vazio
		if ($status == ""){
			$status = "Logs";
		}
		
	}else{
		if ($cor_linha == "#dde4ef") {
			$cor_linha = "white";
		}else{
			$cor_linha = "#dde4ef";
		}
		$cor_fonte = "black";
		
		$erro_descricao = $erro;
		
		$status = $status. " - Ok";
		if ($erro == "0") {
			$erro = "Ok";
		}
	}
	//echo $id;
    //echo "\n";
	//Captura qtde de erros de hoje da tabela de logs
		$sql_erro = "SELECT count(*) as qtde_erros FROM sites_log where id = $id and data_ini >= '$data_hoje' and data_ini <= '$data_hoje 23:59:59'";
		$rs_erro = @mysql_query($sql_erro,$conn);
		while ($row_erro = mysql_fetch_array($rs_erro)) {
			$qtde_erro = $row_erro["qtde_erros"];
		}
		$rs_erro = null;
?>
	<tr bgcolor='<?=$cor_linha?>' title='<?=$url?> | Descricao: <?=$info?> | Erro: <?=$erro_descricao?> | Erros hoje: <?=$qtde_erro?>'>
        <td align="center"><font color="<?=$cor_fonte?>"><b><?=$data?></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><a href='<?=$url?>' target='_new'><?=$descricao?></a></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><a href='sites_log_erros.php?id=<?=$id?>&descricao=<?=$descricao?>' target='_logerros'><?=$status?></a></b></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><a href="#" class="dcontexto" rel="popup console 1250 500"><span><?=$url?> | Descricao: <?=$info?> | Erro: <?=$erro_descricao?></span><?=$erro?></b></a></font></td>
		<td align="center"><font color="<?=$cor_fonte?>"><b><?=$qtde_erro?></b></font></td>
	</tr>
<?php
    //$rs->MoveNext();
}




//$rs->Close();
//$conn->Close();
mysql_close(); 

$rs = null;
$conn = null;

//echo $contador_ok;
if ($contador_ok == 0) {
	echo " 	<tr bgcolor='Green'>
				<td colspan='5' align='center'><font color = 'white'><b>TESTES SITES OK</b></font></td>
			</tr>";
}

//Imprime ajuda
echo " 	<tr bgcolor='#ffffff'>
				<td colspan='5' align='center'>&nbsp;</td>
		</tr>";
echo " 	<tr bgcolor='#CCCCCC'>
				<td colspan='5' align='center'><font color = 'black'><b>Descri&ccedil;&atilde;o dos Status => <a href='https://support.google.com/webmasters/answer/40132?hl=pt-BR' target='_new'>op&ccedil;&atilde;o 1</a> | <a href='http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html' target='_new'>op&ccedil;&atilde;o 2</a></b></font></td>
		</tr>";


?>

 </table>
</body>
</html>