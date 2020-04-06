function despliega_switch() {
	var csDiv = document.getElementById('cajonsup');
	var csminiDiv = document.getElementById('cajonsup_mini');
	if(!csDiv.style.display || csDiv.style.display=='none') {
		csDiv.style.display='block';
		csDiv.style.visibility = 'visible';
		csminiDiv.style.display='none';
		csminiDiv.style.visibility = 'hidden';
	}
	else {
		csDiv.style.visibility = 'hidden';
		csDiv.style.display='none';
		csminiDiv.style.display='block';
		csminiDiv.style.visibility = 'visible';
	}
	
}