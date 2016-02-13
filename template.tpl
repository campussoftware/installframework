<div style="
    border-bottom-color: black;
    margin: 15px;
    border-radius:25px;
    border: 2px;
    display: table;
    border: 2px solid #a1a1a1;  
">
<div id="progressreport" style="width: 650px; padding-top: 10px; margin-left: 50px; margin-top: 30px; font-family: serif;">
<div class="heading">
<div id="organization_details"><span style="font-family:georgia,serif">{ORGANIZATIONLOGO} <span style="color:#0000FF">{ORGANIZATIONNAME} </span> </span></div>
</div>

<div>&nbsp;</div>

<div style="padding-top: 15px;">
<table style="margin-top:15px; width:100%">
	<tbody>
		<tr>
			<td>Admission No</td>
			<td>:</td>
			<td>{ADMISSIONNO}</td>
			<td>Name</td>
			<td>:</td>
			<td>{NAME}</td>
		</tr>
		<tr>
			<td>Branch</td>
			<td>:</td>
			<td>{BRANCH}</td>
			<td>Course</td>
			<td>:</td>
			<td>{COURSE}</td>
		</tr>
		<tr>
			<td>Class</td>
			<td>:</td>
			<td>{CLASS}</td>
			<td>Section</td>
			<td>:</td>
			<td>{SECTION}</td>
		</tr>
	</tbody>
</table>
</div>

<div class="examnamediv" style="text-align: center;"><strong><span style="color:#ff3300">{EXAMNAME} </span></strong></div>

<div id="marksdetails" style="text-align: center; width:600px;">
<div>
<table align="center" border="2px;" style="border-collapse:collapse; margin-top:20px; text-align:center; width:600px">
	<tbody><!--<rhead>-->
		<tr>
			<th>Subject</th>
			<th>Max Marks</th>
			<!--<exnamelist>-->
			<th>{ENAME}</th>
			<!--</exnamelist>-->
			<th>Grade</th>
			<th>Grade Points</th>
			<th>Remarks</th>
		</tr>
		<!--</rhead>--><!--<data>-->
		<tr>
			<td style="text-align:left"><strong>{SUBJECTNAME}</strong></td>
			<td>{MM}</td>
			<!--<exrs>-->
			<td>{OBTAINEDMARKS}</td>
			<!--</exrs>-->
			<td>{GRADE}</td>
			<td>{GRADEPOINTS}</td>
			<td>{REMARKS}</td>
		</tr>
		<!--</data>-->
	</tbody>
</table>
</div>

<div>
<table align="center" style="margin-top:20px; text-align:center; width:600px">
	<tbody>
		<tr>
			<td>GRADE TOTAL</td>
			<td>:</td>
			<td>{GRANDTOTAL}</td>
			<td>Final Grade</td>
			<td>:</td>
			<td>{FINALGRADE}</td>
			<td>GPA</td>
			<td>:</td>
			<td>{GPA}</td>
		</tr>
	</tbody>
</table>
</div>

<div style="display: table; padding-top: 15px;">Overall Remarks:<strong>{OVERALLREMARKS}</strong></div>
</div>

<div style="text-align: right; margin-top: 20px; padding-right: 18px;padding-bottom: 13px;">
<div style="height:30px;">&nbsp;</div>

<div style="font-weight: bold;">Authorised Signature</div>
</div>
</div>
</div>
