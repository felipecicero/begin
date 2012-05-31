$(document).ready(function() {
	
	$("tfoot").css("display", "none");
	$("#hide").css("display", "none");
	
	$('#show').click(function() {		
		$("tfoot").show();
		$("#hide").show();
		$("#show").hide();
	});
	
	$('#hide').click(function() {		
		$("tfoot").hide();
		$("#hide").hide();
		$("#show").show();
	});
	
	
	$('input[name="telefone"]').setMask('phone'); // telefone
	$('input[name="celular"]').setMask('phone'); // telefone
	
	$('input[name="cep"]').setMask('99.999-999'); // cep
	$('input[name="cep_devedor"]').setMask('99.999-999');
	$('input[name="cep_cedente"]').setMask('99.999-999');
	$('input[name="cep_apresentante"]').setMask('99.999-999');
	$('input[name="cep_sacador"]').setMask('99.999-999');
	$('input[name="inicio"]').setMask('99.999-999');
	$('input[name="limite"]').setMask('99.999-999');
	
	
	$('input[name="nascimento"]').setMask('date'); // data
	$('input[name="dataemissaotitulo"]').setMask('date');
	$('input[name="datavencimentotitulo"]').setMask('date');
	$('input[name="vigencia"]').setMask('date');
	$('input[name="date"]').setMask('date');
	
	
	$('input[name="valor"]').setMask('decimal'); // dinheiro
	$('input[name="valortitulo"]').setMask('decimal');
	$('input[name="saldotitulo"]').setMask('decimal');
	$('input[name="valorcustascartorio"]').setMask('decimal'); 
	$('input[name="intim_out"]').setMask('decimal');
	$('input[name="conducao"]').setMask('decimal');
	$('input[name="certidao"]').setMask('decimal');
	$('input[name="taxajudiciaria"]').setMask('decimal');
	$('input[name="emolumento"]').setMask('decimal');
	$('input[name="valor_inicial"]').setMask('decimal');
	$('input[name="valor_final"]').setMask('decimal');
	
	
	
	$('input[name="documento"]').setMask('cnpj');
	$('input[name="tipo_documento"]').click(function(){
		if($(this).val() == '2'){	
			$('input[name="documento"]').setMask('cpf');		
		}else{
			$('input[name="documento"]').setMask('cnpj');
		}
	})
	
	
	$('input[name="documento_devedor"]').setMask('cnpj');
	$('input[name="tipo_identificacao_devedor"]').change(function(){
		if($(this).val() == '2'){	
			$('input[name="documento_devedor"]').setMask('cpf');		
		}else{
			$('input[name="documento_devedor"]').setMask('cnpj');
		}
	})
	
	$('input[name="documento_cedente"]').setMask('cnpj');
	$('input[name="tipo_identificacao_cedente"]').change(function(){
		if($(this).val() == '2'){	
			$('input[name="documento_cedente"]').setMask('cpf');		
		}else{
			$('input[name="documento_cedente"]').setMask('cnpj');
		}
	})
	
	$('input[name="documento_sacador"]').setMask('cnpj');
	$('input[name="tipo_identificacao_sacador"]').change(function(){
		if($(this).val() == '2'){	
			$('input[name="documento_sacador"]').setMask('cpf');		
		}else{
			$('input[name="documento_sacador"]').setMask('cnpj');
		}
	})
	
	$('input[name="documento_apresentante"]').setMask('cnpj');
	$('input[name="tipo_identificacao_apresentante"]').change(function(){
		if($(this).val() == '2'){	
			$('input[name="documento_apresentante"]').setMask('cpf');		
		}else{
			$('input[name="documento_apresentante"]').setMask('cnpj');
		}
	})
	
	
	$('input[name="documento"]').setMask('cnpj');
	$('input[name="tipo"]').change(function(){		
		if($(this).val() == '2'){	
			$('input[name="documento"]').setMask('cpf');		
		}else{
			$('input[name="documento]').setMask('cnpj');
		}
	})
			
	$('input[name="cnpj"]').setMask('cnpj');
		
	
	

	
	oTable = $('#tabela').dataTable({
		"oLanguage": {
			"oPaginate": {
	        "sFirst":    "Primeiro",
	        "sPrevious": "Anterior",
	        "sNext":     "Seguinte",
	        "sLast":     "Último"},
	        	
			"sProcessing":   "Processando...",
		    "sLengthMenu":   "Mostrar _MENU_ registros",
		    "sZeroRecords":  "Não foram encontrados resultados",
		    "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros",
		    "sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
		    "sInfoFiltered": "(filtrado de _MAX_ registros no total)",	    
		    "sSearch":       "Buscar:"	    
		},
		
		"bPaginate": true,
		"bJQueryUI": false,
		"sPaginationType": "full_numbers"
			
	});
	
	$("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );
     
    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
     * the footer
     */
    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );
	
	$('#submitbutton').click(function() {	
		$('#dialog-modal').dialog({
			show: { effect: 'fade' , duration: 1000 },
			height: 66,
			modal: true,
			closeOnEscape: false,
			closeText: 'hide',
			resizable: false
		});
	});
});


function startProgress()
{
    var iFrame = document.createElement('iframe');
    document.getElementsByTagName('body')[0].appendChild(iFrame);
    iFrame.src = 'MyController.php';
}

function Zend_ProgressBar_Update(data)
{
    document.getElementById('pg-percent').style.width = data.percent + '%';
    document.getElementById('pg-text-1').innerHTML = data.text;
    document.getElementById('pg-text-2').innerHTML = data.text;
}

function Zend_ProgressBar_Finish()
{
    document.getElementById('pg-percent').style.width = '100%';
    document.getElementById('pg-text-1').innerHTML = 'done';
    document.getElementById('pg-text-2').innerHTML = 'done';
}

