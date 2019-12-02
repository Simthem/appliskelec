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
    //function calcul() {

    //HOURS------------------------------

    var total_h = 0;
    var totaux_h = 0;
    totaux_h = document.querySelectorAll('.hours');

    var i, nb = totaux_h.length;

    for (i = 0; i < nb; i += 1) {
        total_h += parseFloat(totaux_h[i].value);
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
    }


    //HOURS/NIGHT----------------------------

    var night_h = 0;
    var tot_h_night = 0;
    tot_h_night = document.querySelectorAll('.night_h');

    var j, y = tot_h_night.length;

    for (j = 0; j < y; j += 1) {
        night_h += parseFloat(tot_h_night[j].value);
    }


    //MINUTES/NIGHT----------------------------

    var night_m = 0;
    var tot_m_night = 0;
    tot_m_night = document.querySelectorAll('.night_m');

    var z = tot_m_night.length;

    for (j = 0; j < z; j += 1) {
        night_m += parseFloat(tot_m_night[j].value);
    }


    var h_night = new Array();

    for (j = 0; j < z; j += 1) {
        h_night[j] = tot_h_night[j].value + ':' + tot_m_night[j].value;
        document.getElementById('tot_h_night'+j).value = h_night[j];
        document.getElementById('tot_h_night'+j).value = h_night[j];
    }


    //GLOBAL-----------------------------

    var hours = 0;
    hours = total_h.toFixed(0);
    var minutes = 0;
    minutes = total_m.toFixed(0);
    
    if (minutes > 59) {
        hours = (total_h + 1).toFixed(0);
        minutes -= 60;
    }
    if (minutes > 10) {
        minutes = minutes;
    } else if (minutes < 10 && minutes > 0) {
        minutes = "0" . minutes;
    } else {
        minutes = "00";
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
        $("div[name='flag_chant']").removeClass('d-none');
        $("div[name='flag_day']").removeClass('d-block');
        $("div[name='flag_day']").addClass('d-none');
        $("input[name='ab_day']").prop("checked", false);
        $("div[name='flag_chant']").addClass('d-block');
        console.log('entrée_chantier');
    }
    if (!($("input[name='chantier']").is(":checked"))) {
        $("div[name='flag_chant']").removeClass('d-block');
        $("div[name='flag_chant']").addClass('d-none');
        console.log('sortie_chantier');
    }
});

$('#ab_day').change(function () {
     
    if ($("input[name='ab_day']").is(":checked")) {
        $("input[name='ab_day']").prop('checked', true);
        $("div[name='flag_day']").removeClass('d-none');
        $("div[name='flag_chant']").removeClass('d-block');
        $("div[name='flag_chant']").addClass('d-none');
        $("input[name='chantier']").prop("checked", false);
        $("div[name='flag_day']").addClass('d-block');
        console.log('entrée_day');
    }
    if (!($("input[name='ab_day']").is(":checked"))) {
        $("div[name='flag_day']").removeClass('d-block');
        $("div[name='flag_day']").addClass('d-none');
        console.log('sortie_day');
    }
});


//function PREVIEW_FORM_INDEX

function preview1() {
    var calen = document.forms['inter'].elements['up_inter'].value;
    document.location.href = 'index.php?store='+calen;
}


function preview2() {

    //NORM_HOUR_RECAP _  AND _ PREPARE_SEND_FORM
    var h_index = document.getElementById('h_index').value;
    var total = 0;

    if (document.getElementById('h_index').textContent == '-' . h_index) {
        total = h_index * -1;
        //console.log(total);
    } else {
        total = h_index;
    }
    //total = h_index;
    document.getElementById("intervention_hours").value = total;

    var m_index = document.getElementById('m_index').value;
    var total = 0;
    
    m_index /= 60;
    total = h_index +'.'+ m_index * 100;
    document.getElementById("intervention_hours").value = total;
    document.getElementById("intervention_hours").textContent = total;
    //console.log(document.getElementById("intervention_hours").value);

    var inter_h = document.getElementById("inter_h");
    var temp = document.getElementById("intervention_hours").value;
    var temp_h = parseInt(temp, 10);
    var temp_m = (temp * 100 - temp_h * 100) / 100 * 60;

    if (temp_m < 10) {
        temp_m = '0' + temp_m;
    }
    inter_h.value = temp_h +' h '+ temp_m;

//_____________________________________________________________________________________________________________________________


    //NIGHT_HOUR_RECAP _  AND _ PREPARE_SEND_FORM
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
        //console.log(temp_ni);
        //console.log(temp_h_ni);
        //console.log(temp_m_ni);
        if (temp_m_ni < 10) {
            temp_m_ni = '0' + temp_m_ni;
        }
        h_night.value = temp_h_ni +' h '+ temp_m_ni;
    };


//_________________________________________________________________________________________________________________________
    //THE_REST

    var chant_name = document.getElementById("chant_name");
    chant_name.value = document.getElementById("chantier_name").value;
    var pan_rep = document.getElementById("pan_rep");
    pan_rep.value = document.getElementById("panier_repas").value;

    var pan_rep = document.getElementById("pan_rep");
    if ($("input[name='panier_repas']").is(":checked")) {
        pan_rep.value = document.getElementById("panier_repas").value;
    } else {
        pan_rep.value = 0;
    }

    var com = document.getElementById("com");
    com.value = document.getElementById("commit").value;
    
}




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