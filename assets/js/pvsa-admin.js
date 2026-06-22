document.addEventListener( 'DOMContentLoaded', function() {
	var groups = document.querySelectorAll( 'h3[id^="ohsa-group-"]' );
	groups.forEach( function( group ) {
		var groupId = group.id;
		var table   = document.getElementById( groupId + '-table' );
		if ( table ) {
			// Restore state from localStorage
			if ( localStorage.getItem( groupId ) === 'none' ) {
				table.style.display = 'none';
			}

			// Add click listener
			group.addEventListener( 'click', function() {
				table.style.display = ( table.style.display === 'none' ? '' : 'none' );
				localStorage.setItem( groupId, table.style.display );
			} );
		}
	} );
} );
