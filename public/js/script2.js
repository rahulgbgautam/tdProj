//  $(document).ready(function(){
//  	$('#myform').submit(function(e){
//  	});
// })
            
// const notFound = () => {
// 	let notFound = document.getElementById("notFound");
// 	notFound.innerHTML = "Domain Not Found";
// }

// const searchFun = () => {
// 	let filter = document.getElementById('myInputs').value.toUpperCase();
// 	let myTable = document.getElementById('dataTableExample');
// 	let tr = myTable.getElementsByTagName('tr');
// 	for(var i=0;i<tr.length;i++){
// 		let td=tr[i].getElementsByTagName('td')[0];
// 		if(td){
// 			let textvalue = td.textContent || td.innerHTML;
// 			let value=textvalue.toUpperCase();
// 			let result = filter.localeCompare(value);

// 			console.log(value);
// 			console.log(result);

// 		}
// 	}
// }


const categoryFilter = (value) => {
	var openURL = "probs-sub-category?category_id="+value;
	window.open(openURL,"_self"); 
}

const SubscriptionTypeFilter = (value) => {
	var openURL = "transaction-history?subscription_type="+value;
	window.open(openURL,"_self"); 
}



