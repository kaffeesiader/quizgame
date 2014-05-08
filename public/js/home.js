/**
 * 
 */

function update_highscore(data) {
	console.log(data);
	
	if(data.length > 0) {
		$("#highscore").show();
		
		// first remove all existing rows from table
		$('#highscore-table tbody tr').remove();
		// now create a row for each entry in the highscore list 
		for (var i = 0; i < data.length; i++) {
		    var entry = data[i];
		    // build the html for the table row to insert
		    var row = '<tr>';
		    row += '<td>' + entry.name + '</td>';
		    row += '<td>' + entry.score + '</td>';
		    row += '<td>' + entry.games_won + '</td>';
		    row += '<td>' + entry.games_lost + '</td>';
		    row += '<td>' + entry.games_undecided + '</td>';
		    row += '</tr>';
		    console.log(row);
		    // append our highscore table with the new row
		    $('#highscore-table tbody').append(row);
		}
		
	} else {
		$("#highscore").hide();
	}
}

function read_highscore() {
	// specify how many entries the highscore list should contain
	var count = 5;
	$.get("/quizgame/game/highscore/" + count, update_highscore, 'json');
}

$().ready(function() {
	// read current highscore each 30 seconds
	window.setInterval(read_highscore, 30000);
	read_highscore();
	
});
