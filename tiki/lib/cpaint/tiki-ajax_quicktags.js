var category = '';
var offset = 0;
var sort_mode = 'taglabel_asc';
var filter = '';

function handle_quicktags_list_objects(result) {
    alert(1);
	if (!result[0]) {
		wipeList(0);
		updatePageCount(result);
		document.getElementById('ajaxLoading').style.display = 'none';
		return;
	}

	for (i=0; i < result.length; i++) {
	    for (var j=0; j<ajax_cols.length; j++) {
		name = ajax_cols[j][0];
		
		if ( ajax_cols[j][1] == 'innerHTML' ) {
		    document.getElementById( id ).innerHTML = result[i][name];
		} else if ( ajax_cols[j][1] == 'a' ) {
		    document.getElementById( id ).innerHTML = result[i][name];
		    document.getElementById( id ).href = result[i][name];
		}
	    }
	}

	if (result.length < maxRecords) { // Need to wipe out the rest
	    wipeList(result.length);
	}

	updatePageCount(result);
	document.getElementById('ajaxLoading').style.display = 'none';
}

function listObjects(tag) {
    load('quicktags_list_objects',category,offset,sort_mode,filter);
    document.getElementById('ajaxLoading').style.display = 'block';
    return;

	currentTag = tag;
	var cp = new cpaint();
	//cp.set_debug(2);

	document.getElementById('ajaxLoading').style.display = 'block';

	cp.call('tiki-ajax_quicktags.php', 'list_objects', renderObjectList, category, offset, sort_mode, filter);
}

function renderObjectList(result) {
	var objects = result.ajaxResponse[0].object;

	if (!objects) {
		wipeList(0);
		updatePageCount(result);
		document.getElementById('ajaxLoading').style.display = 'none';
		return;
	}

	for (i=0; i < objects.length; i++) {
		for (var j=0; j<ajax_cols.length; j++) {
			name = ajax_cols[j][0];
			id = ajax_cols[j][0] + '_' + i;
			if ( ajax_cols[j][1] == 'innerHTML' ) {
				document.getElementById( id ).innerHTML = objects[i].find_item_by_id(name, id).data;
			} else if ( ajax_cols[j][1] == 'a' ) {
				document.getElementById( id ).innerHTML = objects[i].find_item_by_id(name, id).data;
				document.getElementById( id ).href = objects[i].find_item_by_id(name, id).data;
			}
		}
	}

	if (objects.length < maxRecords) { // Need to wipe out the rest
		wipeList(objects.length)
	}

	updatePageCount(result);
	document.getElementById('ajaxLoading').style.display = 'none';
}
