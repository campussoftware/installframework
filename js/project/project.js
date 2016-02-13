function getfeestructure()
{
    var formdata = $("form#"+$("#node").val()).serialize();
    var posturl=window.hosturl+'student_feeplan_details'+"/getFeeStructure";
    if($("#cur_list_academicyear_id").val()=='')
    {
        displayPopupAlert("Please Select Academic Year ");
        return false;
    }
    
    if($("#student_admission_id").val()=='' && $("#admision_no").val()=='')
    {
        displayPopupAlert("Please Select/Enter Student/Admission No ");
        return false;
    }
    
    $.ajax({
    url : posturl,
    type : "POST",
    dataType : "html",
    data : formdata,
    success : function (html)
    {
        var ivid="#feedetails_div";
        $(ivid).html(html);	
        if($("#transactionId").val())
        {
            $("#payment_details_print").show();
        }
        else
        {
            $("#payment_details_print").hide();
        }
        return true;

    }
    });

}
function make_payment()
{
     $('#payment_details_submit').prop("disabled",true);
        var x=confirm("Do You Want to Submit");
        var count=0;
	if(x==true)
	{
            var formdata = $("form#financefeereceipt").serialize();
            var amount=document.getElementById('amount').value;
            if(isNaN(amount))
            {
                    $('#amount').val("0");
                    displayPopupAlert("Amount should be  Numbers Only"); 
                    $('#payment_details_submit').prop("disabled",false);
                    return false;
            }
            if(amount<=0)
            {
                    displayPopupAlert("Amount should be greater than 0 ");
                    $('#payment_details_submit').prop("disabled",false);
                    return false;
            }
            var payment_type=$('#core_payment_type').val();
            if(payment_type=="")
            {
                displayPopupAlert(" Please Select Payment Type ");
                $('#payment_details_submit').prop("disabled",false);
                    return false;
            }
            var posturl=window.hosturl+$("#node").val()+"/makePayment";
            
            $('#payment_details_submit').prop("disabled",true);
            $.ajax({
                    url:posturl,
                    data:formdata,
                    type:"POST",
                    dataType:"html",
                    success:function(responseData)
                    {
                        try
                        {
                            var obj = jQuery.parseJSON(responseData)
                            if(obj.status=="success")
                            {  
                                getfeestructure();
                                $("#transactionId").val(obj.transactionId);
                            }
                            else
                            {
                                $('#payment_details_submit').prop("disabled",false);
                                $("#payment_details_errors").html(obj.message);
                            }
                        }
                        catch(e)
                        {
                            $('#payment_details_submit').prop("disabled",false);
                            $("#payment_details_errors").html(responseData);			
                        }
                    }
                    });
                }
                else
                {
                     $('#payment_details_submit').prop("disabled",false);
                }
}
function make_concession()
{
    $('#consession_details_submit').prop("disabled",true);
    var x=confirm("Do You Want to Submit");
    var count=0;
    if(x==true)
    {
	var formdata = $("form#financeconcession").serialize();
	var amount=document.getElementById('amount').value;
	if(isNaN(amount))
	{
		$('#amount').val("0");
		displayPopupAlert("Amount should be  Numbers Only"); 
                $('#consession_details_submit').prop("disabled",false);
                return false;
	}
	if(amount<=0)
	{
		displayPopupAlert("Amount should be greater than 0 ");
                $('#consession_details_submit').prop("disabled",false);
		return false;
	}
	var payment_type=$('#fee_concession_type').val();
        if(payment_type=="")
        {
            displayPopupAlert(" Please Select Concession Type ");
            $('#consession_details_submit').prop("disabled",false);
		return false;
        }
        var posturl=window.hosturl+$("#node").val()+"/makeConcession";
        
	$('#consession_details_submit').prop("disabled",true);
	$.ajax({
		url:posturl,
		data:formdata,
		type:"POST",
		dataType:"html",
		success:function(responseData)
		{
                    try
                    {
                        var obj = jQuery.parseJSON(responseData)
                        if(obj.status=="success")
                        {  
                            getfeestructure();                           
                        }
                        else
                        {
                            $("#payment_details_errors").html(obj.message);
                        }
                    }
                    catch(e)
                    {
			$("#payment_details_errors").html(responseData);			
                    }
		}
		});
            }
            else
            {
                $('#consession_details_submit').prop("disabled",false);
            }
}
function feeReceiptPrint(transactionId)
{
    var posturl=window.hosturl+'transaction_logs'+"/print/"+transactionId;
    window.open(posturl);
    return false;
}
function showreferenceno(type)
{
	if(type=='CS')
	{
		$('#reference_no_div').hide();
		$('#reference_no').val("");
	}
	else
	{
		$('#reference_no_div').show();
	}
	return true;
}
function getbranchcourses(type)
{
    console.log(type);
    var posturl=window.hosturl+'studentshift'+"/getBranchCourses";
    if(type=='filter')
    {
        var formData=$("form#result_studentshift").serialize();
    }
    else
    {
        var formData=$("form#mradata_studentshift").serialize();
    }
    $.ajax({
		url:posturl,
		data:formData+"&type="+type,
		type:"POST",
		dataType:"html",
		success:function(responseData)
		{
                    if(type=='filter')
                    {                        
                        $("#div_search_branch_orientation_id").html(responseData);
                    }
                    else
                    {
                        $("#mra_cur_branch_orientation_id").html(responseData);
                    }
                }
            });
    
}
function getbranchclasess(type)
{
    
    var posturl=window.hosturl+'studentshift'+"/getBranchClasses";
    if(type=='filter')
    {
        var formData=$("form#result_studentshift").serialize();
    }
    else
    {
        var formData=$("form#mradata_studentshift").serialize();
    }
    $.ajax({
		url:posturl,
		data:formData+"&type="+type,
		type:"POST",
		dataType:"html",
		success:function(responseData)
		{
                    if(type=='filter')
                    {                        
                        $("#div_search_branch_class_id").html(responseData);
                    }
                    else
                    {
                        $("#mra_cur_branch_class_id").html(responseData);
                    }                   
                }
            });
    
}
function getbranchsections(type)
{
    
    var posturl=window.hosturl+'studentshift'+"/getBranchSections";
    if(type=='filter')
    {
        var formData=$("form#result_studentshift").serialize();
    }
    else
    {
        var formData=$("form#mradata_studentshift").serialize();
    }
    $.ajax({
		url:posturl,
		data:formData+"&type="+type,
		type:"POST",
		dataType:"html",
		success:function(responseData)
		{
                    if(type=='filter')
                    {                        
                        $("#div_search_branch_class_section_id").html(responseData);
                    }
                    else
                    {
                        $("#mra_cur_branch_class_section_id").html(responseData);
                    }                   
                    
                }
            });
    
}
function getprogressreport()
{
    var formdata = $("form#"+$("#node").val()).serialize();
    var posturl=window.hosturl+$("#node").val()+"/getProgressReport";
    
    if($("#cur_list_academicyear_id").val()=='')
    {
        displayPopupAlert("Please Select Academic Year ");
        return false;
    }   
    if($("#cur_list_branch_id").val()=='')
    {
        displayPopupAlert("Please Select Branch ");
        return false;
    }
    if($("#cur_branch_orientation_id").val()=='')
    {
        displayPopupAlert("Please Select Branch Course");
        return false;
    }
    if($("#cur_branch_class_id").val()=='')
    {
        displayPopupAlert("Please Select Branch Class");
        return false;
    }
    if($("#acd_exam_group_id").val()=='')
    {
        displayPopupAlert("Please Select Exam Group ");
        return false;
    }
    $.ajax({
    url : posturl,
    type : "POST",
    dataType : "html",
    data : formdata,
    success : function (html)
    {
        var ivid="#progressreport_result_div";
        $(ivid).html(html);								
        return true;

    }
    });
}
function shiftstudentvalidate()
{
    if($("#search_list_acdemicyear_id").val()=='')
    {
        displayPopupAlert("Please Select Academic Year ");
        return false;
    }
    if($("#search_list_branch_id").val()=='')
    {
        displayPopupAlert("Please Select Branch ");
        return false;
    }
    if($("#search_branch_orientation_id").val()=='')
    {
        displayPopupAlert("Please Select Branch Course ");
        return false;
    }
    if($("#search_branch_class_id").val()=='')
    {
        displayPopupAlert("Please Select Branch Class ");
        return false;
    }
    return false;
}