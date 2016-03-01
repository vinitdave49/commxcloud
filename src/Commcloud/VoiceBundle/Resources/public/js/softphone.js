(function($){

 /* Get a Twilio Client token with an AJAX request */ 
 $(document).ready(function() { 
 
	 var answerButton = $("#answer");
	 var hangUpButton = $("#hangup");
	 var makeCallButton = $("#makecall");
	 var callStatus = $("#status");
	 
	 function updateCallStatus(status) { 
		 callStatus.text(status); 
	 } 
	 var path = $("#abc").attr("data-path");
     $.post(path, function(data) { 
         // Set up the Twilio Client Device with the token 
		 console.log(data);
		// data = JSON.parse(data[0]);
		 //console.log(data);
        Twilio.Device.setup(data[0].token, {debug : true, rtc : true}); 
		window.worker = new Twilio.TaskRouter.Worker(data[0].workerToken);
        registerTaskRouterCallbacks(); 
     });
	 
 
 
 Twilio.Device.ready(function (device) {
    updateCallStatus("Ready to start call");
	$("#status").html("<h4><span style='color:#333'>Ready to start call</span></h4>");
 });

 Twilio.Device.error(function (error) {
    updateCallStatus("Error: " + error.message);
    $("#status").html("<h4><span style='color:#F00'>Error: "+error.message+"</span></h4>");
 });

 Twilio.Device.connect(function (conn) {
	 console.log('Successfully connected');
    hangUpButton.prop("disabled", true);
	answerButton.prop("disabled", true);
    updateCallStatus("Successfully Established a call");
    $("#status").html("<h4><span style='color:#0F0'>Successfully established call.</span></h4>");
	conn.disconnect(function(conn) {
		console.log("the call has ended");
	});
 });

 Twilio.Device.disconnect(function (conn) {
    hangUpButton.prop("disabled", true);
	answerButton.prop("disabled", false);
    updateCallStatus("Call Ended. Ready to take next call");
    $("#status").html("<h4><span style='color:#333'>Call ended.</span></h4>");
 });
 
 Twilio.Device.presence(function(presenceEvent) { 
    updateCallStatus("Presence Event: " + presenceEvent.from + " " + presenceEvent.available);
 }); 

 Twilio.Device.incoming(function (conn) {
    updateCallStatus("Incoming support call: " + conn.parameters.From);
	console.log("Incoming call status: " + conn.status);
	
	//$("#incomingcallalert").html("<h3 class='top-div-text'> Call from"+conn.parameters.From+" </h3>");
	conn.accept(function() { 
         updateCallStatus("In call with customer"); 
    }); 

	if (confirm("Accept incoming call from " + conn.parameters.From + "?")){
		conn.accept();
		console.log("Incoming call status after accepting: " + conn.status);
	}
	
 });			
 
 hangUpButton.click(function(){
	Twilio.Device.disconnectAll();
 });
 
 makeCallButton.click(function(){
	params = {"PhoneNumber": $("#exampleInputPhone").val()};
	Twilio.Device.connect(params);
 });
 
 function logger(message) {
//	var log = document.getElementById('log');
//	log.value += "\n> " + message;
//	log.scrollTop = log.scrollHeight;
 }	
 
 $('#workerActivity').change(function(){
	var nextActivitySid = $("#workerActivity option:selected").val();
	console.log("Activity Change Requested: " + $("#workerActivity option:selected").text());
	worker.update("ActivitySid", nextActivitySid);
 });
	
 $('#signOut').click(function(){
	offlineActivitySid = "WA7b56deb0d7cd97a69b663e4a62532ac7";
	worker.update("ActivitySid", offlineActivitySid);
	//window.location.href='index.php';
 });
			
 function registerTaskRouterCallbacks() {
	  worker.on('ready', function(worker){
		  //agentActivityChanged(worker.activityName);
		  logger("Successfully registered as: " + worker.friendlyName);
		  logger("Current activity is: " + worker.activityName);
		  console.log(worker.sid);
		  console.log(worker.friendlyName);
	  });
	 
	  worker.on('activity.update', function(worker){
		  //agentActivityChanged(worker.activityName);
		  logger("Worker activity changed to: " + worker.activityName);
	  });
	 
	  worker.on("reservation.created", function(reservation){
		logger("-----");
		logger("You have been reserved to handle a call!");
		logger("Call from: " + reservation.task.attributes.from);
		logger("Selected language: " + reservation.task.attributes.selected_language);
		logger("-----");
	  });
	 
	  worker.on("reservation.accepted", function(reservation){
		logger("Reservation " + reservation.sid + " accepted!");
	  });
	 
	  worker.on("reservation.rejected", function(task){
		logger("Reservation " + reservation.sid + " rejected!");
	  });
	 
	  worker.on("reservation.timeout", function(task){
		logger("Reservation " + reservation.sid + " timed out!");
	  });
	 
	  worker.on("reservation.canceled", function(task){
		logger("Reservation " + task.reservation_sid + " canceled!");
	  });
 }			
 
 $.each(['0','1','2','3','4','5','6','7','8','9','star','pound'], function(index, value) { 
	$('#button' + value).click(function(){
		if (value=='star')
			$("#exampleInputPhone").text(function(i, origText){
				$("#exampleInputPhone").val(origText+"*");
				return origText+"*";
			});
		else if (value=='pound')
			$("#exampleInputPhone").text(function(i, origText){
				$("#exampleInputPhone").val(origText+"#");
				return origText+"#";
			});
		else
			$("#exampleInputPhone").text(function(i, origText){
				$("#exampleInputPhone").val(origText+value);
				return origText+value;
			});							
		return false;
	});
 });
  $('.phone-header').click(function(){
		   $('#phone-box-content').slideToggle('slow');
  });
  $('.chat-header').click(function(){
		   $('#chat-box-content').slideToggle('slow');
  });

		fixScrollTop();
		function findElementTotalOffset(obj) {
		    var oleft = otop = 0;
		    if (obj.offsetParent) {
		        do {
		            oleft += obj.offsetLeft;
		            otop += obj.offsetTop;
		        } while (obj = obj.offsetParent);
		    }
		    return {left : oleft, top : otop};
		}
		function isVisible(elment) {
			var vpH = $(window).height(), // Viewport Height
				st = $(window).scrollTop(), // Scroll Top
				y = findElementTotalOffset($(elment)).top;
			return vpH <= (y + st);
		}
		
		$(window).scroll(function() {
		    fixScrollTop();
        });
		
		function fixScrollTop(){
		  $('.phone-box').css('bottom','0px');
			 if (isVisible($('#main-footer'))) {
			    console.log("footer is visible");
				$('.phone-box').css('bottom','50px');
			} else {
			    console.log("footer is not visible");
			    $('.phone-box').css('bottom','0px');
				//$('.phone-header').css('position','fixed');
				//$('.phone-header').fadeIn();
			}
		}
 }); 
 
 
 
})(jQuery);