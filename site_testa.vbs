'Engine responsável por efetuar os testes nas URLs e manter os resultados dos testes atualizados no BD
on error resume next
'Abre conexao com o DB
set conexao = createobject("adodb.connection")
conexao.open("dsn=myspb;uid=USER;pwd=PASSWORD;database=monitools")

'Set shell = WScript.CreateObject("WScript.Shell")
'PathAplic = "regedit.exe -s c:\inetpub\wwwroot\monitools\proxy\proxy001.reg"
'set proxy = shell.exec(PathAplic)
'dir_path = "C:\Program Files\Web\scripts\"
'erro = 0
'err.clear
'=============================================================

'Prepara para abrir URLs
'Set objHTTP = CreateObject("MSXML2.XMLHTTP")
'Set objHTTP = CreateObject("MsXml2.ServerXmlHttp.4.0")
Set objHTTP = CreateObject("MsXml2.ServerXmlHttp.6.0")
'objHTTP.setProxy 2,"10.100.250.243:3128"
objHTTP.SetOption 2, 13056 ' Ignore all SSL errors
'Debug.Print WEBPAGE

'Captura as URLs a serem testadas
sql = "SELECT id, url FROM sites WHERE ativo = 1 ORDER BY descricao"
'Executa comando SQL
'response.write sql
set rsBanco = conexao.execute(sql)

if not isempty(rsbanco) then
	do while not rsbanco.eof
		'Limpa erro
		err.clear
		status = ""
		erro = ""
		
		'Captura a URL para teste
		id = rsbanco("id").value
		url = rsbanco("url").value
		'msgbox url
		
		'Faz o teste
		objHTTP.Open "GET", url, FALSE
		objHTTP.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
		objHTTP.Send

		'msgbox objHTTP.ResponseText
		'msgbox objHTTP.Status
		'msgbox objHTTP.statusText
		'msgbox err.number
		'msgbox err.description
		'MsgBox "Erro número #" & Err.Number & " na Linha " & Erl & " - " & Err.Description & " - gerado por " & Err.Source
		if (err.number < 0) then
			erro = "Erro número #" & Err.Number & " - " & Err.Description
			'erro = "Erro número #" & Err.Number & " - " & Err.Description & " - gerado por " & Err.Source
		else
			erro = Err.Number
		end if
		'msgbox erro
		status = objHTTP.Status
		'erro = err.number
		data_registro = FormataData(now())
		
		'Se der erro negativo pega descrição do erro
		'if (err.number < 0) then
			'erro = err.number&" - "&err.description
		'end if
			
		
		upd_sql = "update sites set status = '"&status&"', erro = '"&erro&"', data = '"&data_registro&"' where id = '"&id&"'"
		'msgbox upd_sql
		conexao.execute(upd_sql)
		
		'Grava ou Limpa log
		if (status <> "200") or (err.number <> 0) then
			'Grava os dados no BD
			'Verifica se já não tem registro de alarme
			sql = "select count(*) as qtde from sites_log where id = '"&id&"' and data_fim is null"
			'msgbox sql
			set rs_qtde = conexao.execute(sql)
			qtde = rs_qtde("qtde").value
			
			if qtde = "0" then
				'Insere no BD o alerta
				sql2 = "insert into sites_log (id, status, data_ini, erro, contador) values ('"&id&"', '"&status&"' , '"&data_registro&"', '"&erro&"', '1')"
				'msgbox sql2
				conexao.execute(sql2)
			else
				'Verifica qual o valor de contador e soma 1
				sql = "select contador from sites_log where id = '"&id&"' and data_fim is null"
				set rs_cont = conexao.execute(sql)
				contador = rs_cont("contador").value
				contador = contador + 1
				
				sql2 = "update sites_log set contador = '"&contador&"', status = '"&status&"' where id = '"&id&"' and data_fim is null"
				'msgbox sql2
				conexao.execute(sql2)
			end if
		else
			sql2 = "update sites_log set data_fim = '"&data_registro&"' where id = '"&id&"' and data_fim is null"
			'msgbox sql2
			conexao.execute(sql2)
		end if
		
		
	rsbanco.movenext
	loop
end if

'Fecha conexao com DB
conexao.close


'Função de formatação de data
function FormataData(byval DataArq)
	hora = hour(DataArq)
	if (hora >=0) and (hora < 10) then
	    hora = 0&hora
	end if
	minuto = minute(DataArq)
	if (minuto >=0) and (minuto < 10) then
	    minuto = 0&minuto
	end if
	segundos = second(DataArq)
	if (segundos >=0) and (segundos < 10) then
	    segundos = 0&segundos
	end if
	mes = month(DataArq)
	if (mes >=0) and (mes < 10) then
	    mes = 0&mes
	end if
	dia = day(DataArq)
	if (dia >=0) and (dia < 10) then
	    dia = 0&dia
	end if
	FormataData = year(DataArq)&"-"&mes&"-"&dia&" "&hora&":"&minuto&":"&segundos
end function