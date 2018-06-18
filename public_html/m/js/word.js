var downCt = 0;
$(function(){
	$("#albummm").attr("top", "0");
	boStart("D");
});

function boStart(tp){
	clearInterval($("#boList").attr("timer"));
	
	if(tp == "D"){ //위로 이동
		imgUp();
		$("#boList").attr("timer", setInterval("imgUp()", 4000)); // 멈춰있는 시간

	}else{ //아래로 이동
		if(downCt == 0){
			var leng = $("#boList div").size();
			$("#boList").css("top",parseInt($("#boList div").eq(0).height()*-1));
			$("#boList>div").eq(parseInt(leng-1)).clone().prependTo($("#boList"));
			$("#boList>div").eq(leng).remove();
			downCt = 1;
		}
		imgDown();
		$("#boList").attr("timer", setInterval("imgDown()", 5000));
	}

}



function imgUp(){
	$("#boList").animate({
		top : parseInt($("#boList div").eq(0).height() * -1)
	},300,function(){
		$("#boList").css("top", "0px");
		$("#boList>div").eq(0).clone().appendTo($("#boList"));
		$("#boList>div").eq(0).remove();
	});
}

function imgDown(){
	var leng = $("#boList div").size();
	$("#boList").animate({
		top : 0
	},300,function(){
		$("#boList").css("top", "0px");
		$("#boList").css("top",parseInt($("#boList div").eq(0).height()*-1));
		$("#boList>div").eq(parseInt(leng-1)).clone().prependTo($("#boList"));
		$("#boList>div").eq(leng).remove();
	});
}
