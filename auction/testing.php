
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="../css/bootstrapTest.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css" type="text/css" media="screen"/>
</head>

<body>
<div class="container">
    <form action="" class="form-horizontal"  role="form">
        <fieldset>
            <legend>Test</legend>
            <div class="form-group">
                <label for="dtp_input1" class="col-md-2 control-label">DateTime Picking</label>
                <div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
				<input type="hidden" id="dtp_input1" value="" /><br/>
            </div>
			<div class="form-group">
                <label for="dtp_input2" class="col-md-2 control-label">Date Picking</label>
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
				<input type="hidden" id="dtp_input2" value="" /><br/>
            </div>
			<div class="form-group">
                <label for="dtp_input3" class="col-md-2 control-label">Time Picking</label>
                <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                    <input class="form-control" size="16" type="text" value="" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                </div>
				<input type="hidden" id="dtp_input3" value="" /><br/>
            </div>
        </fieldset>
    </form>
</div>

<div class="form-group">
          <label for="dtp_input3" class="col-md-2 control-label">Time Picking</label>
          <div class="input-append date form_datetime1" data-date="2013-02-21T15:25:00Z">
              <input size="16" type="text" value="" readonly>
              <span class="add-on"><i class="icon-remove"></i></span>
              <span class="add-on"><i class="icon-calendar"></i></span>
          </div>

<script type="text/javascript" src="../scripts/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="../scripts/bootstrap.min.test.js"></script>
<script type="text/javascript" src="../scripts/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../scripts/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">

$(".form_datetime1").datetimepicker({
    format: "dd MM yyyy - hh:ii",
    autoclose: true,
    todayBtn: true,
    startDate: "2013-02-14 10:00",
    minuteStep: 10
});

$('.form_datetime').datetimepicker({
    //language:  'fr',
    weekStart: 1,
    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
    showMeridian: 1
  });

	$('.form_date').datetimepicker({
    language:  'fr',
    weekStart: 1,
    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
  });

	$('.form_time').datetimepicker({
    language:  'fr',
    weekStart: 1,
    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
  });
</script>

</body>
</html>
