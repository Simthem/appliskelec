//function FOR EXTRACT_FILE_CSV

function pre_extract(n_inp) {

    if ($("#username").val() != 0) {
        document.getElementById('pre_ext').innerHTML = "";

        var users = document.getElementById("username");
        users.value = document.getElementById("username").value;

        var newInput1 = document.createElement("input");
        newInput1.id = n_inp;
        newInput1.type = "text";
        newInput1.name = n_inp;
        newInput1.value = users.value;
        newInput1.style = 'width: 33%';
        newInput1.className = 'col-3 p-0 text-center border-0';

        var newInput2 = document.createElement("a");
        newInput2.name = n_inp;
        newInput2.type = 'button';
        newInput2.onclick = 'remove_inp('+n_inp+')';
        newInput2.className = 'mt-auto mr-2 mb-auto ml-0 fas fa-trash-alt';
        liste.appendChild(newInput1);
        liste.appendChild(newInput2);
    }
}

function remove_inp(n_inp) {
    liste.removeChild(document.getElementById(n_inp));
}

function extract() {

    var start = document.forms['extraction'].elements['beg_ext'].value;
    var stop = document.forms['extraction'].elements['end_ext'].value;
    if ($("input[name='all']").is(":checked")) {
        var u_tot = document.forms['extraction'].elements['all'].value;
    } else {
        var u_tot = 0;
    }
    if ($("input[name='a_name']").is(":checked")) {
        var admin = document.forms['extraction'].elements['a_name'].value;
    } else {
        var admin = 0;
    }

    if (start.value != '' && stop.value != '') {
        return ext_link(start, stop, u_tot, admin);
    }
}

function ext_link(start, stop, u_tot, admin) {
    document.location.href = 'extract_obj.php?beg='+start+'&end='+stop+'&all='+u_tot+'&a_name='+admin;
}




//function CALCUL_VALID_DAY

$('#verif').on('click', function() {

    //HOURS------------------------------

    var total_h = 0;
    var totaux_h = 0;
    totaux_h = document.querySelectorAll('.hours');

    var i, nb = totaux_h.length;

    var up_ab = null;

    for (i = 0; i < nb; i += 1) {
        total_h += parseFloat(totaux_h[i].value);
        console.log(totaux_h[i].value);
        if (totaux_h[i].value == null || totaux_h[i].value == 0) {
            up_ab = i;
        }
    }

    //MINUTES----------------------------

    var total_m = 0;
    var totaux_m = 0;
    totaux_m = document.querySelectorAll('.minutes');

    var x = totaux_m.length;

    for (i = 0; i < x; i += 1) {
        total_m += parseFloat(totaux_m[i].value);
    }


    var tot_h = new Array();

    for (i = 0; i < nb; i += 1) {

        tot_h[i] = totaux_h[i].value + ':' + totaux_m[i].value;
        document.getElementById('tot_h'+i).value = tot_h[i];
        document.getElementById('tot_h'+i).textContent = tot_h[i];
        document.getElementById('tot_h_ab'+i).value = 0;
        document.getElementById('tot_h_ab'+i).textContent = 0;
    }

    

    //HOURS/NIGHT----------------------------

    var night_h = 0;
    var tot_h_night = 0;
    tot_h_night = document.querySelectorAll('.night_h');

    var y = tot_h_night.length;

    for (i = 0; i < y; i += 1) {
        night_h += parseFloat(tot_h_night[i].value);
    }


    //MINUTES/NIGHT----------------------------

    var night_m = 0;
    var tot_m_night = 0;
    tot_m_night = document.querySelectorAll('.night_m');

    var z = tot_m_night.length;

    for (i = 0; i < z; i += 1) {
        night_m += parseFloat(tot_m_night[i].value);
    }


    var arr_night = new Array();

    for (i = 0; i <= z; i += 1) {

        if (tot_h_night[i] != null && tot_h_night[i] != 0) {
            arr_night[i] = tot_h_night[i].value + ':' + tot_m_night[i].value;
            document.getElementById('tot_h_night'+i).value = arr_night[i];
            document.getElementById('tot_h_night'+i).text_content = arr_night[i];
            document.getElementById('tot_h_ab'+i).value = 0;
            document.getElementById('tot_h_ab'+i).textContent = 0;
        }
    }

    if (up_ab != null) {

        //ABSENCE_HOURS----------------------
        var total_h_ab = 0;
        var totaux_h_ab = 0;
        totaux_h_ab = document.querySelectorAll('.h_ab');

        var i, ab = totaux_h_ab.length;

        for (i = 0; i < ab; i += 1) {
            total_h_ab += parseFloat(totaux_h_ab[i].value);
            console.log(total_h_ab);
        }


        //ABSENCE_MINUTES--------------------


        var total_m_ab = 0;
        var totaux_m_ab = 0;
        totaux_m_ab = document.querySelectorAll('.m_ab');

        var k = totaux_m_ab.length;

        for (i = 0; i < k; i += 1) {
            total_m_ab += parseFloat(totaux_m_ab[i].value);
            console.log(total_m_ab);
        }

        var tot_h_ab = new Array();

        for (i = 0; i < ab; i += 1) {

            tot_h_ab[i] = totaux_h_ab[i].value + ':' + totaux_m_ab[i].value;
            document.getElementById('tot_h_ab'+(i)).value = tot_h_ab[i];
            document.getElementById('tot_h_ab'+(i)).textContent = tot_h_ab[i];
        }
    }


    if (up_ab != null) {
        total_h_ab = Number(total_h_ab);
        console.log('first h_ab = '+total_h_ab);
        if (total_m_ab < 0 && total_h_ab != 0) {
            console.log(total_m_ab);
            total_m_ab *= -1;
            console.log(total_m_ab);
            total_m_ab = Number(total_m_ab / 60);
            console.log(total_m_ab);
        } else {
            console.log('tg');
            total_m_ab = Number(total_m_ab / 60);
        }
        total_h = Number(total_h).toFixed(0);
        total_m /= 60;
        total_m = Number(total_m);
        while (total_m >= 1) {
            total_h = Number(total_h) + 1;
            total_m = Number(total_m) - 1;
        }
        console.log(total_m);
    } else {
        total_h = Number(total_h).toFixed(0);
        total_m = Number(total_m);
    }


    if (total_m_ab >= 0) {
        tot_ab_glo = Number(total_h_ab) +'.'+ Number(total_m_ab * 10);
    } else {
        total_m_ab *= -1;
        tot_ab_glo = (Number(total_h_ab) +'.'+ Number(total_m_ab * 10)) * -1;
    }
    tot_glo = Number(total_h) +'.'+ Number(total_m * 10);
    global = Number(tot_glo) - Number(tot_ab_glo);

    console.log(tot_ab_glo);
    console.log(tot_glo);
    console.log(global);

    var hours = 0;
    var minutes = 0;

    if (global < 0 && global > -1 && global != global.toFixed(0)) {
        hours = "-0";
        minutes = ((global.toFixed(0) - global) * 60) * -1;
    } else {
        hours = parseInt(global);
        minutes = (global.toFixed(0) - global) * 60;
        if (minutes == 0) {
            minutes = "00";
        } else if (minutes < 0) {
            minutes *= -1;
        }
    }

    document.getElementById('recap_h').value = hours;
    document.getElementById('recap_h').textContent = hours;
    document.getElementById('recap_m').value = minutes;
    document.getElementById('recap_m').textContent = minutes;



    var hours_night = 0;
    hours_night = night_h.toFixed(0);
    var min_night = 0;
    min_night = night_m.toFixed(0);
    
    if (min_night > 59) {
        hours_night = (night_h + 1).toFixed(0);
        min_night -= 60;
    }
    if (min_night > 10) {
        min_night = min_night;
    } else if (min_night < 10 && min_night > 0) {
        min_night = "0" . min_night;
    } else {
        min_night = "00";
    }

    document.getElementById('rec_h_night').value = hours_night;
    document.getElementById('rec_h_night').textContent = hours_night;
    document.getElementById('rec_m_night').value = min_night;
    document.getElementById('rec_m_night').textContent = min_night;

    var pan_rep = document.getElementById("pan_rep");
    if ($("input[name='panier_repas']").is(":checked")) {
        pan_rep.value = document.getElementById("panier_repas").value;
    } else {
        pan_rep.value = 0;
    }
});

function change() {
    document.getElementById("flag").value = 1;
}

var chantier = document.querySelectorAll(".chantier");
var a, count = chantier.length;

$('.chantier').change(function() {
    for (a = 0; a < count; a++) {
        document.getElementById('chantier_id'+a).value = chantier[a].value;
    }
});


//function PREVIEW_FORM_ABSENCE

function date_ab(id) {
    var calen = document.forms['sign_ab'].elements['up_inter'].value;
    document.location.href = 'absence.php?id='+id+'&store='+calen;
}

$('#chantier').change(function () {

    if ($("input[name='chantier']").is(":checked")) {
        $("input[name='chantier']").prop("checked", true);
        document.getElementById('chantier').value = 1;
        $("div[name='flag_chant']").removeClass('d-none');
        $("div[name='flag_day']").removeClass('d-block');
        $("div[name='flag_day']").addClass('d-none');
        $("input[name='ab_day']").prop("checked", false);
        document.getElementById('ab_day').value = null;
        $("div[name='flag_chant']").addClass('d-block');
        $("div[name='flag_desc']").removeClass('d-block');
        $("div[name='flag_desc']").addClass('d-none');
        if ($("div[id='preview']").hasClass('in')) {
            $("div[id='preview']").removeClass('in');
        }
        $("div[id='preview']").removeClass('d-none');
        //console.log('chantier = '+document.getElementById('chantier').value);
        //console.log('ab_day = '+document.getElementById('ab_day').value);
    }
    if (!($("input[name='chantier']").is(":checked"))) {
        $("div[name='flag_chant']").removeClass('d-block');
        $("div[name='flag_chant']").addClass('d-none');
        $("div[id='preview']").addClass('d-none');
        $("div[id='preview']").removeClass('in');
        document.getElementById('chantier').value = null;
        //console.log('chantier = '+document.getElementById('chantier').value);
    }

    if (!($("input[name='ab_day']").is(":checked")) && !($("input[name='chantier']").is(":checked"))) {
        $("div[name='flag_desc']").removeClass('d-none');
        $("div[name='flag_desc']").addClass('d-block');
    }
});

$('#ab_day').change(function () {
     
    if ($("input[name='ab_day']").is(":checked")) {
        $("input[name='ab_day']").prop('checked', true);
        document.getElementById('ab_day').value = 1;
        $("div[name='flag_day']").removeClass('d-none');
        $("div[name='flag_chant']").removeClass('d-block');
        $("div[name='flag_chant']").addClass('d-none');
        $("input[name='chantier']").prop("checked", false);
        document.getElementById('chantier').value = null;
        $("div[name='flag_day']").addClass('d-block');
        $("div[name='flag_desc']").removeClass('d-block');
        $("div[name='flag_desc']").addClass('d-none');
        if ($("div[id='preview']").hasClass('in')) {
            $("div[id='preview']").removeClass('in');
        }
        $("div[id='preview']").removeClass('d-none');
        //console.log('chantier = '+document.getElementById('chantier').value);
        //console.log('ab_day = '+document.getElementById('ab_day').value);
    }
    if (!($("input[name='ab_day']").is(":checked"))) {
        $("div[name='flag_day']").removeClass('d-block');
        $("div[name='flag_day']").addClass('d-none');
        $("div[id='preview']").addClass('d-none');
        $("div[id='preview']").removeClass('in');
        document.getElementById('ab_day').value = null;
        //console.log('ab_day = '+document.getElementById('ab_day').value);
    }

    if (!($("input[name='ab_day']").is(":checked")) && !($("input[name='chantier']").is(":checked"))) {
        $("div[name='flag_desc']").removeClass('d-none');
        $("div[name='flag_desc']").addClass('d-block');
    }
});



//function PREVIEW_FORM_INDEX AND SEARCH_FORM

function preview1() {
    var calen = '?store='+document.forms['inter'].elements['up_inter'].value;
    var curr_page = window.location.href;
    var store = curr_page.substr(curr_page.indexOf('?'));

    if (curr_page.indexOf('?') == -1 && calen != '?store=') {
        document.location.href = curr_page + calen;
    } else if (store && store != calen && calen != '?store=') {
        curr_page = window.location.href.replace(store, calen);
        document.location.href = curr_page;
    } else {
        curr_page = window.location.href.replace(store, '');
        document.location.href = curr_page;
    }
}

function preview_bet() {
    var calen = '&bet='+document.forms['inter'].elements['bet_inter'].value;
    var curr_page = window.location.href;
    var bet = curr_page.substr(curr_page.indexOf('&'));
    var store = curr_page.substr(curr_page.indexOf('?') - curr_page.indexOf('&'));

    if (store == curr_page) {
        alert("Vous n'avez pas renseigné le champ 'Par date'. Veuillez le renseigner AVANT de vouloir effectuer une recherche 'Par période'.");
        document.location.href = curr_page;
        return false;
    } else if (document.forms['inter'].elements['bet_inter'].value < document.forms['inter'].elements['up_inter'].value) {
        alert("Votre demande ne peut aboutir : veuillez renseigner dans le champ 'Par période' une date supérieure à celle du champ 'Par date'.");
        if (curr_page.indexOf('&') != -1) {
            document.location.href = curr_page.replace(bet, '');
        } else {
            document.location.href = curr_page;
        }
        return false;
    }

    if (curr_page.indexOf('&') == -1 && calen != '&bet=') {
        document.location.href = curr_page + calen;
    } else if (bet && bet != calen && calen != '&bet=') {
        curr_page = window.location.href.replace(bet, calen);
        document.location.href = curr_page;
    } else {
        curr_page = window.location.href.replace(bet, '');
        document.location.href = curr_page;
    }
}

function preview_user() {
    var user = 'user='+document.forms['inter'].elements['user'].value;
    console.log(user);
    var curr_page = window.location.href;
    var tot_get = curr_page.substr(curr_page.indexOf('?'));

    if (tot_get.indexOf('&user') == -1 && tot_get.indexOf('&bet') != -1) {
        var bet_get = tot_get.substr(tot_get.indexOf('&'));
        var store_get = tot_get.replace(bet_get, '');
    } else if (tot_get.indexOf('?store') != -1 && tot_get.indexOf('&user') == -1 && tot_get.indexOf('&bet') == -1 && user != 'user=') {
        var store_get = tot_get;
    } else if (tot_get.indexOf('&user') != -1) {
        if (tot_get.indexOf('&bet') == -1) {
            var store_get = tot_get.replace(tot_get.substr(tot_get.indexOf('&user')), '');
        } else {
            var bet_get = (tot_get.substr(tot_get.indexOf('&bet'))).replace(tot_get.substr(tot_get.indexOf('&user')), '');
            var store_get = tot_get.replace(bet_get + tot_get.substr(tot_get.indexOf('&user')), '');
        }
    } else {
        var store_get;
    }

    if (store_get && bet_get) {
        var temp = store_get;
        temp += bet_get;
        var bef_us = tot_get.replace(temp, '');
    } else if (store_get) {
        var bef_us = tot_get.replace(store_get, '');
    } else if (tot_get.indexOf('?') != -1) {
        var bef_us = tot_get;
    } else {
        var bef_us;
    }

    if (bet_get && store_get) {
        if (store_get && bet_get && bef_us == '') {
            curr_page = window.location.href +'&'+ user;
            document.location.href = curr_page;
        } else if (store_get && bet_get && bef_us != '&'+user && bef_us != '') {
            if (user != 'user=') {
                curr_page = window.location.href.replace(bef_us, '&'+user);
                document.location.href = curr_page;
            } else {
                curr_page = window.location.href.replace(bef_us, '');
                document.location.href = curr_page;
            }
        }
    } else if (store_get) {
        if (store_get && bef_us == '') {
            curr_page = window.location.href +'&'+ user;
            document.location.href = curr_page;
        } else if (store_get && bef_us != '&'+user && user != 'user=') {
            curr_page = window.location.href.replace(bef_us, '&'+ user);
            document.location.href = curr_page;
        } else if (bef_us && user == 'user=') {
            curr_page = window.location.href.replace(bef_us, '');
            document.location.href = curr_page;
        }
    } else {
        if (bef_us && user != 'user=' && bef_us != '?'+user) {
            curr_page = window.location.href.replace(bef_us, '?'+ user);
            document.location.href = curr_page;
        } else if (user != 'user=') {
            curr_page = window.location.href +'?'+ user;
            document.location.href = curr_page;
        } else {
            curr_page = window.location.href.replace(bef_us, '');
            document.location.href = curr_page;
        }
    }
}


function preview2(day) {

    if (!day) {
        //NORM_HOUR_RECAP _  AND _ PREPARE_SEND_FORM
        var h_index = document.getElementById('h_index').value;
        var total = 0;
        total = h_index;

        document.getElementById("intervention_hours").value = total;

        var m_index = document.getElementById('m_index').value;
        var total = 0;
        
        m_index /= 60;
        total = h_index +'.'+ m_index * 100;
        document.getElementById("intervention_hours").value = total;
        document.getElementById("intervention_hours").textContent = total;

        var inter_h = document.getElementById("inter_h");
        var temp = document.getElementById("intervention_hours").value;
        var temp_h = parseInt(temp, 10);
        if (temp_h < 0) {
            var temp_m = (temp * -100 - temp_h * -100) / 100 * 60;
        } else {
            var temp_m = (temp * 100 - temp_h * 100) / 100 * 60;
        }

        if (temp_m < 10) {
            temp_m = '0' + temp_m;
        }
        inter_h.value = temp_h +' h '+ temp_m;

        var chant_name = document.getElementById("chant_name");
        chant_name.value = document.getElementById("chantier_name").value;

        var com = document.getElementById("com");
        com.value = document.getElementById("commit").value;

    } else {
        document.getElementById("intervention_hours").value = 7;
        document.getElementById("intervention_hours").textContent = 7.0;

        var inter_h = document.getElementById("inter_h");
        inter_h.value = "7 h 00";

        var chant_name = document.getElementById("chant_name");
        document.getElementById("chantier_name").value = document.getElementById("day").value;
        chant_name.value = document.getElementById("day").value;

        var com = document.getElementById("com");
        com.value = document.getElementById("com_day").value;
    }


    if ($("input[name='panier_repas']").val()) {

        var ni_h_index = document.getElementById('ni_h_index').value;
        var ni_total = 0;

        ni_total = ni_h_index;
        document.getElementById("night_hours").value = ni_total;

        var ni_m_index = document.getElementById('ni_m_index').value;
        var ni_total = 0;
        
        ni_m_index /= 60;
        ni_total = ni_h_index +'.'+ ni_m_index * 100;
        document.getElementById("night_hours").value = ni_total;
        document.getElementById("night_hours").textContent = ni_total;


        if ($("input[name='coch_night']").is(":checked")) {
            var h_night = document.getElementById("h_night");
            var temp_ni = document.getElementById("night_hours").value;
            var temp_h_ni = parseInt(temp_ni, 10);
            var temp_m_ni = (temp_ni * 100 - temp_h_ni * 100) / 100 * 60;
            if (temp_m_ni < 10) {
                temp_m_ni = '0' + temp_m_ni;
            }
            h_night.value = temp_h_ni +' h '+ temp_m_ni;
        };


        var pan_rep = document.getElementById("pan_rep");
        if ($("input[name='panier_repas']").is(":checked")) {
            pan_rep.value = document.getElementById("panier_repas").value;
        } else {
            pan_rep.value = 0;
        }
    }
}


$("input[id='submit_int']").on('click', function () {
    var temp = document.getElementById("intervention_hours").value;

    if (!temp || temp == null || temp == 0) {
        alert("Vous n'avez complété aucune heure dans le champ réservé, aucune intervention/absence n'aura donc été enregistrée.");
        return false;
    }
});




//function DELETE_USER

function reply_click_user(clicked_id){
    var id = clicked_id;

    if (confirm('Are you sure to remove this record ?'))
    {
        $.ajax({
            url: '../api/user/delete_user.php',
            type: 'GET',
            data: {id: id},
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {
                //$("#"+id).remove();
                alert("Record removed successfully");
                location.reload();
            }
        });
    }
};




//function DELETE_TROUBLES

function reply_click_troubles(clicked_id){
    var id = clicked_id;

    if (confirm('Are you sure to remove this record ?'))
    {
        $.ajax({
            url: '../api/troubleshooting/delete_troubles.php',
            type: 'GET',
            data: {id: id},
            error: function() {
                alert('Something is wrong');
            },
            success: function(data) {
                //$("#"+id).remove();
                alert("Record removed successfully");
                location.reload();
            }
        });
    }
};



//nav choice for troubleshooting list

$('.nav-pills a:last').on('click', function() {
    $('.nav-pills a:first').removeClass('active'); // remove active class from tabs
    $(this).addClass('active'); // add active class to clicked tab
    $('.tab-content #tab1').hide(); // hide all tab content
    $('.tab-content #tab2').show();
});

$('.nav-pills a:first').on('click', function() {
    $('.nav-pills a:last').removeClass('active'); // remove active class from tabs
    $(this).addClass('active'); // add active class to clicked tab
    $('.tab-content #tab2').hide(); // hide all tab content
    $('.tab-content #tab1').show();
});




//check formula in add_profil

function checkFUser() {

    if (document.getElementById('username1').value == "") {
        alert('Vous devez indiquer un nom d\'utilisateur obligatoirement !');
        return false;
    } else if (document.getElementById('first_name').value == "") {
        alert('Vous devez indiquer un prénom obligatoirement');
        return false;
    } else if (document.getElementById('phone').value == "") {
        alert('Vous devez indiquer un téléphone obligatoirement');
        return false;
    } else {
        const regex = /^[+0-9]{10,12}$/gm;
        var str = document.getElementById('phone').value;
        
        if (!(str.match(regex))) {
            alert('Le numéro de téléphone n\'est pas valide');
            return false;
        } else {
            document.getElementById('add_u').submit();
        }
    }
}




//check formula in index

function checkForm(){
    if (document.getElementById('name').value == ""){
        alert('Vous devez indiquer un libellé obligatoirement !');
        return false;
    }
    
    if (document.getElementById('contact_name').value == "") {
        alert('Vous devez indiquer un nom de contact obligatoirement !');
        return false;
    }

    const regex = /^[+0-9]{10,12}$/gm;
    var str = document.getElementById('contact_phone').value;
    
    if (!(str.match(regex))) {
        alert('Le numéro de téléphone n\'est pas valide');
        return false;
    } else {
        document.getElementById('add_trouble').submit();
    }
}




//Slide

!function(a){
    function b(){
        g.hasClass(k)?h.toggleClass(l):h.toggleClass(m),q&&g.one("transitionend",function(){
            q.focus()
        })
    }
    function c(){
        g.hasClass(k)?h.removeClass(l):h.removeClass(m)
    }
    function d(){
        g.hasClass(k)?(h.addClass(l),g.animate({left:"0px"},r),i.animate({left:s},r),j.animate({left:s},r)):(h.addClass(m),g.animate({right:"0px"},r),i.animate({right:s},r),j.animate({right:s},r)),q&&q.focus()
    }
    function e(){
        g.hasClass(k)?(h.removeClass(l),g.animate({left:"-"+s},r),i.animate({left:"0px"},r),j.animate({left:"0px"},r)):(h.removeClass(m),g.animate({right:"-"+s},r),i.animate({right:"0px"},r),j.animate({right:"0px"},r))
    }
    function f(){
        a(t).addClass(v),a(t).on("click",function(b){
            var c=a(this);c.hasClass(v)?(c.siblings(t).addClass(v).removeClass(u),c.removeClass(v).addClass(u)):c.addClass(v).removeClass(u),b.stopPropagation()
        })
    }
    var g=a(".menu"),h=a("body"),i=a("#container"),j=a(".push"),k="left-menu",l="left-open",m="right-open",n=a(".site-overlay"),o=a(".menu-btn, .menu-link"),p=a(".menu-btn"),q=a(g.data("focus")),r=200,s=g.width()+"px",t=".pushy-submenu",u="pushy-submenu-open",v="pushy-submenu-closed";
    a(t);
    a(document).keyup(function(a){27==a.keyCode&&(h.hasClass(l)||h.hasClass(m))&&(w?c():(e(),x=!1),p&&p.focus())});
    var w=function(){
        var a=document.createElement("p"),b=!1,c={webkitTransform:"-webkit-transform",OTransform:"-o-transform",msTransform:"-ms-transform",MozTransform:"-moz-transform",transform:"transform"};
        if(null!==document.body){
            document.body.insertBefore(a,null);
            for(var d in c)void 0!==a.style[d]&&(a.style[d]="translate3d(1px,1px,1px)",b=window.getComputedStyle(a).getPropertyValue(c[d]));
            return document.body.removeChild(a),void 0!==b&&b.length>0&&"none"!==b
        }
        return!1
    }();
    
    if(w)f(),o.on("click",function(){b()}),n.on("click",function(){b()});
    else{
        h.addClass("no-csstransforms3d"),g.hasClass(k)?g.css({left:"-"+s}):g.css({right:"-"+s}),i.css({"overflow-x":"hidden"});
        var x=!1;f(),o.on("click",function(){
            x?(e(),x=!1):(d(),x=!0)
        }),n.on("click",function(){
            x?(e(),x=!1):(d(),x=!0)
        })
    }
}(jQuery);