function imageFormatter( value, row ) {


    var svg_clr_setting = row.svg_clr;

    if ( svg_clr_setting != null && svg_clr_setting == 1 ) {
        var imageUrl = value;

        if ( value ) {

            if ( imageUrl.split( '.' ).pop() === 'svg' ) {

                return '<embed class="svg-img" src="' + value + '">';
            } else {
                console.log( value );
                return '<a class="image-popup-no-margins" href="' + value + '"><img class="rounded avatar-md shadow img-fluid" alt="" src="' + value + '" width="55"></a>';
            }
        }
    } else {
        return ( value !== '' ) ? '<a class="image-popup-no-margins" href="' + value + '"><img class="rounded avatar-md shadow img-fluid" alt="" src="' + value + '" width="55"></a>' : '';
    }


}

function sub_category( value, row ) {
    return '<a href="get_sub_categories/' + row.id + '"> <div class="category_count">' + value + ' Sub Categories</div></a>';
}

function custom_fields( value, row ) {

    var rootUrl = window.location.protocol + '//' + window.location.host;
    return '<a href="' + rootUrl + '/category_custom_fields/' + row.id + '"> <div class="category_count">' + value + ' Custom Fields</div></a>';

}

function status_switch( value, row ) {

    status = value == "1" ? "checked" : "";
    return '<div class="form-check form-switch" style="padding-left: 5.2rem;"> <input class = "form-check-input switch1"id = "' + row.id +
        '"onclick = "chk(this);" data-url="' + row.edit_status_url + '" type = "checkbox" role = "switch"' + status + ' value = ' + value + ' ></div>';
}
function premium_status_switch( value, row ) {

    status = value == "1" ? "checked" : "";
    return '<div class="form-check form-switch" style="padding-left: 5.2rem;"> <input class = "form-check-input switch1"id = "' + row.id +
        '"onclick = "chk(this);" data-url="updateaccessability" type = "checkbox" role = "switch"' + status + ' value = ' + value + ' ></div>';
}


function badge( value, row ) {
    if ( value == "review" ) {
        badgClass = 'primary';
        badgeText = 'Under Review';
    }
    if ( value == "approve" ) {
        badgClass = 'success';
        badgeText = 'Approved';
    }
    if ( value == "reject" ) {
        badgClass = 'danger';
        badgeText = 'Rejected';
    }
    return '<span class="badge rounded-pill bg-' + badgClass +
        '">' + badgeText + '</span>';
}





function property_type( value ) {
    if ( value == 0 ) {
        return '<div class="sell_type">Sell</div>';
    } else if ( value == 1 ) {
        return '<div class="rent_type">Rent</div>';

    } else if ( value == 2 ) {
        return "Sold";
    } else if ( value == 3 ) {
        return "Rented";
    }
}



function status_badge( value, row ) {
    if ( value == '0' ) {
        badgClass = 'danger';
        badgeText = 'OFF';
    } else {
        badgClass = 'success';
        badgeText = 'ON';
    }
    return '<span class="badge rounded-pill bg-' + badgClass +
        '">' + badgeText + '</span>';
}

function user_status_badge( value, row ) {
    if ( value == '0' ) {
        badgClass = 'danger';
        badgeText = 'Inacive';
    } else {
        badgClass = 'success';
        badgeText = 'Active';
    }
    return '<span class="badge rounded-pill bg-' + badgClass +
        '">' + badgeText + '</span>';
}

function style_app( value, row ) {
    return '<a class="image-popup-no-margins" href="images/app_styles/' + value + '.png"><img src="images/app_styles/' + value + '.png" alt="style_4"  height="60" width="60" class="rounded avatar-md shadow img-fluid"></a>';
}

function filters( value ) {


    if ( value == "most_liked" ) {

        filter = "Most Liked";
    } else if ( value == "price_criteria" ) {
        filter = "Price Criteria";
    } else if ( value == "category_criteria" ) {
        filter = "Category Criteria";
    } else if ( value == "most_viewed" ) {
        filter = "Most Viewed";
    }
    return filter;
}

function adminFile( value, row ) {
    return "<a href='languages/" + row.code + ".json ' )+' > View File < /a>";

}

function appFile( value, row ) {
    return "<a href='lang/" + row.code + ".json ' )+' > View File < /a>";
}
