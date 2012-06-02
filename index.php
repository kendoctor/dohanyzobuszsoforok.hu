<?php
function draw_select($name, $number_of_items, $start_number=0, $default_number=null, $names=null)
{
    print "<select id=\"$name\">";
    if ($default_number === null) {
        print "<option value=\"\"></option>";
    }
    for ($i=$start_number; $i<$start_number+$number_of_items; $i++) {
        print "<option value=\"".($i<10 ? "0$i" : $i)."\"" .
              ($default_number!==null && $i==$default_number ? ' selected="selected"' : '') .
              '>' . ($names===null ? ($i<10 ? "0$i" : $i) : $names[$i]) . '</option>';
    }
    print "</select>";
}

$month_names = array('január', 'február', 'március', 'április', 'május', 'június',
                     'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="title" content="DohanyzoBuszsoforok.hu - Füstöljük ki a füstös buszokat!" />
<meta name="description" content="DohanyzoBuszsoforok.hu - Füstöljük ki a füstös buszokat!" />
<meta name="keywords" content="dohányzó, cigiző, buszsofőrök, busz, sofőrök" />
<meta name="author" content="Monda László" />
<meta name="copyright" content="Copyright 2008 Monda László, Minden jog fenntartva" />

<link rel="shortcut icon" href="favicon.ico" />

<title>DohanyzoBuszsoforok.hu - Füstöljük ki a füstös buszokat!</title>

<style type="text/css">

body {
    text-align: center;
}

#main {
    text-align:left;
    width: 800px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 1em;
    border: solid 1px #aaa;
    padding: 1em;
}

.form-row-title {
    text-align: right;
    font-weight: bold;
}

.alert {
    color: #a00;
    text-align: center;
}

.vertical-spacing {
    height:0.5em;
}

form input[type=button] {
    width: 100%;
    text-align: left;
}

#envelope {
    width: 500px;
    height: 300px;
    background-color: #ffe;
    border: solid 1px #aaa;
    padding: 20px;
}

#envelope td {
    vertical-align: top;
}

</style>

<script type="text/javascript" src="jquery-1.2.6.min.js"></script>

<script type="text/javascript">
//<![CDATA[

var mail_text = 'Tisztelt volan-select!\n\n\
start-year. start-month start-day az Önök bus-numberlicense-plate-numberstart-hour:start-minute-kor start-location induló, end-hour:end-minute-kor end-location érkező járatával utaztam.\n\n\
Az utazás közben a sofőr rágyújtott a cigarettájára, ami egyrészt nagyon kellemetlenül érintett lévén, hogy ki nem állhatom a dohányfüstöt, másrészt ezzel a gépkocsivezetőjük több jogszabályt is megsértett:\n\n\
A gépkocsivezető dohányzásával megsértette az 1992. évi XXII. törvény 103. § (1) c) pontját, miszerint köteles ... általában olyan magatartást tanúsítani, hogy ez más egészségét és testi épségét ne veszélyeztesse, mivel a dohányzás nem csak a dohányzónak, hanem környezetének egészségét is súlyosan károsítja.\n\n\
Mivel az 1993. évi XCIII. törvény a munkavédelemről 38. § (2) szerint a nemdohányzók védelme érdekében gondoskodni kell dohányzóhelyek, dohányzóhelyiségek kijelöléséről, de az 1999. évi XLII. törvény 2. § (2) f) alapján nem jelölhető ki dohányzóhely ... menetrend alapján belföldi helyközi közforgalomban közlekedő autóbuszon, így az Önök gépkocsivezetője dohányzásával szabálysértést követett el.\n\n\
Szabálysértését tetézte azzal, hogy ámbár az 1999. évi XLII. törvény 3. § (1) alapján a rágyújtás pillanatában fel kellett volna saját magát szólítania a jogsértés haladéktalan befejezésére, illetőleg kezdeményeznie kellett volna saját magával szemben egészségvédelmi bírság megfizetését is, de ezeket szintén elmulasztotta.\n\n\
A gépkocsivezető dohányzásának a busz egész közönsége tanúja voltwitnesses.\n\n\
A fentiek alapján felszólítom Önöket, hogy a fenti járat gépkocsivezetőjével szemben fegyelmi eljárást folytassanak le, és annak eredményéről tájékoztassanak, valamint tudatosítsák járművezetőikben az autóbuszokon való dohányzás tilalmát!\n\n\
Válaszukat előre is köszönöm!\n\n\
person-location, current-datestamp\n\n\
Tisztelettel,\n\
person-name\n';

function get_day_ending()
{
    var day = $('#start-day').val();
    var endings = [
        '00', 'én', 'án', 'án', 'én', 'én', 'án', 'én', 'án', 'én',
        'én', 'én', 'én', 'án', 'én', 'én', 'án', 'én', 'án', 'én',
        'án', 'én', 'én', 'án', 'én', 'én', 'án', 'én', 'án', 'én',
        'án', 'én'
    ];
    return endings[day.charAt(0)=='0' ? day.substr(1) : day];
}

function transform_word_ending(word)
{
    var last_letter = word.charAt(word.length-1);
    if (last_letter == 'a') {
        last_letter = 'á';
    } else if (last_letter == 'e') {
        last_letter = 'é';
    }
    return word.substring(0, word.length-1) + last_letter;
}

function does_word_sound_low(word)
{
    low_vowels = 'aáoóuú'
    high_vowels = 'eéiíöőüű';
    all_vowels = low_vowels + high_vowels;
    for (var i=word.length-1; i>=0; i-=1 ) {
        if (all_vowels.indexOf(word.charAt(i)) != -1) {
            return low_vowels.indexOf(word.charAt(i)) != -1;
        }
    }
    return true;
}

function get_witness_text(witness_number)
{
    var witness = 'witness' + witness_number + '-';
    if ($('#'+witness+'name').val() &&
        $('#'+witness+'mothers-name').val() &&
        $('#'+witness+'id').val() &&
        $('#'+witness+'address').val())
    {
        return $('#'+witness+'name').val() +
               ' (anyja neve ' + $('#'+witness+'mothers-name').val() +
               ', szem. ig. száma ' + $('#'+witness+'id').val() +
               ', címe ' + $('#'+witness+'address').val() + ')';
    } else {
        return '';
    }
}

var fields = {
    'volan-select': 'a közlekedési társaság nevét',
    'person-name': 'a nevedet',
    'person-location': 'a lakhelyedet',
    'start-location': 'az indulás helyét',
    'start-year': '',
    'start-month': '',
    'start-day': '',
    'start-hour': 'az indulás óráját',
    'start-minute': 'az indulás percét',
    'end-location': 'az érkezés helyét',
    'end-hour': 'az érkezés óráját',
    'end-minute': 'az érkezés percét'
};

function generate_mail()
{
    var actual_mail_text = mail_text;

    // Substitute required fields.
    $.each(fields, function(id, description) {
        var field_value = id == 'start-month' || id == 'volan-select'
            ? $('#' + id + ' option:selected').text()
            : $('#' + id).val() + (id=='start-day' ? '-' + get_day_ending() : '');

        if (id == 'start-location') {
            field_value = transform_word_ending(field_value) +
                          (does_word_sound_low(field_value) ? 'ról' : 'ről');
        } else if (id == 'end-location') {
            field_value = transform_word_ending(field_value) +
                          (does_word_sound_low(field_value) ? 'ra' : 're');
        }
        actual_mail_text = actual_mail_text.replace(id, field_value);
    });

    // Substitute bus number.
    actual_mail_text = actual_mail_text.replace('bus-number',
        $('#bus-number').val()
            ? ($('#bus-number').val() + ' számú ')
            : '');

    // Substitute license plate number.
    actual_mail_text = actual_mail_text.replace('license-plate-number',
        $('#license-plate-number').val()
            ? ($('#license-plate-number').val() + ' rendszámú ')
            : '');

    // Substitute witnesses text.
    var witness1 = get_witness_text(1);
    var witness2 = get_witness_text(2);
    if (witness1 && witness2) {
        witnesses = witness1 + ' és ' + witness2;
    } else if (witness1) {
        witnesses = witness1;
    } else if (witness2) {
        witnesses = witness2;
    } else {
        witnesses = '';
    }
    if (witnesses) {
        witnesses = ', többek közt ' + witnesses;
    }
    actual_mail_text = actual_mail_text.replace('witnesses', witnesses);

    // Substitute datestamp string.
    var date = new Date();
    var month = date.getMonth() + 1;
    month = month < 10 ? '0'+month : month;
    var day = date.getDate();
    day = day < 10 ? '0'+day : day;
    var datestamp = date.getFullYear() + '.' + month + '.' + day + '.';
    actual_mail_text = actual_mail_text.replace('current-datestamp', datestamp);

    $('#mail-editor').val(actual_mail_text);
}

$(function()
{
    $('#person-name').keyup(function() {
        $('#envelope-person-name').text(this.value ? this.value : 'neved');
    }).trigger('keyup');

    $('#person-location').keyup(function() {
        $('#envelope-person-location').text(this.value ? this.value : 'lakhelyed');
    }).trigger('keyup');

    $('#generate-mail-button').click(function() {
        var warning = '';
        $.each(fields, function(id, description) {
            if (description && $('#'+id).val() == '') {
                warning += '<li>' + description + '</li>';
            }
        });

        $('#error-message').hide(500);
        if (warning) {
            warning = 'Elfelejtetted megadni:<ul>' + warning + '</ul>';
            $('#error-message').html(warning).show(500);
        } else if ($('#mail-editor').val()) {
            var confirmation =
                'Biztos vagy benne, hogy felül akarod írni<br />a levél aktuális tartalmát? ' +
                '<input type="button" value="igen" onclick="javascript:generate_mail(); $(\'#error-message\').hide(500)" /> ' +
                '<input type="button" value="nem" onclick="javascript:$(\'#error-message\').hide(500)" />';
            $('#error-message').html(confirmation).show(500);
        } else {
            generate_mail();
        }
    });

    $('form input:button').click(function() {
        $('form').attr('text').value = $('#mail-editor').val();
        $('form').attr('action', 'get-' + this.id + '.php').submit();
    });

    $('#volan-select').change(function() {
        var option = this.options[this.selectedIndex];
        var corporation_name = option.text;

        if (corporation_name) {
            var corporation_address = option.value;
            var postcode_separator_pos = corporation_address.indexOf(' ');
            var city_separator_pos = corporation_address.indexOf(',', postcode_separator_pos);
            var postcode = corporation_address.substring(0, postcode_separator_pos);
            var city = corporation_address.substring(postcode_separator_pos+1, city_separator_pos);
            var street_address = corporation_address.substring(city_separator_pos+2);
            var envelope_address = corporation_name + '<br />' + city + '<br />' +
                                   street_address + '<br /><b>' + postcode + '</b>';
            $('#envelope-address').html(envelope_address);
        } else {
            $('#envelope-address').html('Válaszd ki fent a közlekedési társaságot ahhoz, hogy itt láthasd a címét!');
        }
    }).trigger('change');

    var laci_account = 'laci';
    var laci_domain = 'monda.hu';
    var laci_email = laci_account + '@' + laci_domain;
    var html = '<a href="mailto:' + laci_email + '">' + laci_email + '</a>'
    $('#email').html(html);
});

//]]>
</script>

</head>
<body>
<h1>DohanyzoBuszsoforok.hu</h1>
<h2><i>Füstöljük ki a füstös buszokat!</i></h2>
<div id="main">
<h2 class="alert">Neked is eleged van már abból, hogy füstös buszokon kelljen szagolnod az orrfacsaró dohányfüstöt?<br /><br />Itt az ideje véget vetni ennek!</h2>
<br />

<h1>Miért jött létre ez az oldal?</h1>

<p>Monda Lászlónak hívnak és már középiskolás korom óta rendszeresen busszal utazom az országban.  Alapjában véve szeretem a tömegközlekedésnek ezt a formáját.  A buszon utazva kíméled a környezetet, új emberekkel ismerkedhetsz meg és kényelmesen hátradőlve kellemesen kipihenheted magad.  Egy valamit azonban ki nem állhatok.  Kitaláltad, a dohányfüstöt.</p>
<p>A buszok jelentős részén a sofőr a szabályokra fittyet hányva egyszer csak elővesz egy cigarettát a zsebéből, aztán meghallod az öngyújtó jellegzetes hangját és másodpercekkel később már meg is csapta az orrodat az orrfacsaró bűz.  Engem személy szerint nem igazán érdekel, hogy valaki olyan hülye, hogy tönkrevágja az egészségét és még fizet is érte, amíg a füstöt nem nekem kell szagolnom, de amint azt megérzem mindent elkövetek annak érdekében, hogy egyszer és mindenkorra véget vessek az illető tevékenységének.</p>

<h1>A rövidtávú stratégia: szóbeli felkérés</h1>

<p>A temperamentumomnál fogva már elég korán igen kifinomultan reagáltam az ilyen esetekre.  Nyugisan előresétálok és határozottan előadom a következő mondatot: "Kérem hagyja abba a dohányzást, mert rettentően zavar".  A zsigeri válaszreakciók a megrökönyödöttségtől a felháborodottság parádés skáláján mozognak.  Igen szórakoztató őket megfigyelni, de mindig ugyanúgy végződtek az ilyen esetek: a sofőr elnyomja a cigarettát.</p>
<p>Megdöbbentő a számomra, hogy rajtam kívül senkiről sem tudok, aki hasonlóan járna el.  Vajon miért?  Nos, a társadalom körülbelül egyharmada maga is dohányzik, így őket nem kifejezetten zavarja a füst.  A többieket többé-kevésbé zavarja, főleg attól függően, hogy hol ülnek, de nem állnak ki magukért gondolván, hogy "Hát éppen a sofőr úrnak nem szabadna dohányoznia?  Hiszen ő vezeti a buszt!".  Biztosíthatlak, hogy a sofőrnek ugyanúgy, mint a busz összes utasítsásnak TILOS dohányoznia, ezen túl pedig még a jegy árát is te fizeted szóval miért kéne egy büdös buszon utaznod?</p>
<p>Az az igazság, hogy az emberek többsége túlságosan közömbös, konfliktuskerülő és nem elég asszertív ahhoz, hogy odasétáljon a sofőrhöz és megmondja neki a frankót és talán te is valamelyest közéjük tartozol, de azért készítettem ezt az odalt, hogy tudatosítsam benned, hogy ennek nem kell így lennie.  Azzal, hogy ezt megléped mindenkinek jót teszel.  Jót teszel magadnak, mert újra tiszta levegőt lélegezhetsz a buszon és asszertívabb személlyé válsz, aki kiáll magáért és másokért.  Jót teszel az utastársaidnak, mert nekik sem kell szagolniuk a füstöt.  Végsősoron pedig jót teszel a sofőrnek is egészségügyileg (a kezdeti vérnyomásemelkedésétől eltekintve, haha)!</p>
<p>Ez a szóbeli felszólítás esetenként hatékony, de hosszútávon nem igazán működik, mert bizonyos esetekben kihagyhat a sofőr memóriája és az egyes utazások alkalmával újra szólnod kell neki, hogy nyomja már el a cigit, illetve ha nem vagy a buszon, attól még pöfékel a sofőr másoknak kellemetlen perceket okozva.  Ennek kapcsán eljutunk a hosszútávú stratégiához.</p>

<h1>A hosszútávú stratégia: panaszlevél</h1>

<p>Az alábbiakban egy olyan automatizált rendszert bocsátok a rendelkezésedre, aminek a segítségével pár perc leforgása alatt könnyedén meg tudsz írni egy panaszlevelet.  Csak a lényeges információkat kell megadnod, a többit megírja helyetted a rendszer.</p>

<h2>1. Kulcsinformációk megadása</h2>

<p>Az indulás pontos idejét megtalálhatod a nyugtán, amit a sofőrtől kaptál, ami alapján meg tudod nézni a busz hivatalos indulási és érkezési idejét a <a href="http://www.menetrendek.hu/cgi-bin/menetrend/html.cgi" target="_blank">Volán menetrend oldalán</a>, illetve az adott közlekedési társaság saját oldalán.</p>

<p>Ha helyi járatot akarsz jelenteni akkor az <i>Indulás helye</i> illetve az <i>Érkezés helye</i> mezőkbe a megállók nevét írd be és a <i>Busz száma</i> mezőbe a helyi járat számát.</p>

<h3>Szükséges adatok</h3>

<table>
    <tr>
        <td class="form-row-title">Közlekedési társaság:</td>
        <td>
            <select id="volan-select">
            <option></option>
            <option value="3301 Eger, Pf. 74.">Agria Volán Zrt.</option>
            <option value="8000 Székesfehérvár, Börgöndi u. 14.">Alba Volán Zrt.</option>
            <option value="8200 Veszprém, Pápai u. 30.">Balaton Volán Zrt.</option>
            <option value="8101 Várpalota, Pf. 54.">Bakony Volán Zrt.</option>
            <option value="6501 Baja, Pf. 38.">Bács Volán Zrt.</option>
            <option value="1980 Budapest, Pf. 11.">BKV Zrt.</option>
            <option value="3527 Miskolc, József Attila u. 70.">Borsod Volán Zrt.</option>
            <option value="4025 Debrecen, Salétrom u. 3.">DKV Debreceni Közlekedési Zrt.</option>
            <option value="7100 Szekszárd, Tartsay Vilmos u. 4.">Gemenc Volán Zrt.</option>
            <option value="4031 Debrecen, Szoboszlói u. 4-6.">Hajdú Volán Zrt.</option>
            <option value="3000 Hatvan, Bercsényi u. 82.">Hatvani Volán Zrt.</option>
            <option value="5000 Szolnok, Nagysándor József u. 24.">Jászkun Volán Zrt.</option>
            <option value="7400 Kaposvár, Füredi u. 180.">Kapos Volán Zrt.</option>
            <option value="7400 Kaposvár, Áchim András u. 1/1.">Kaposvári Tömegközlekedési Zrt.</option>
            <option value="9002 Győr, Pf. 29.">Kisalföld Volán Zrt.</option>
            <option value="5602 Békéscsaba, Pf. 36.">Körös Volán Zrt.</option>
            <option value="6000 Kecskemét, Csáktornyai u. 4-6.">Kunság Volán Zrt.</option>
            <option value="3201 Gyöngyös, Pf. 114.">Mátra Volán Zrt.</option>
            <option value="3502 Miskolc, Pf. 226.">Miskolc Városi Közlekedési Zrt.</option>
            <option value="3101 Salgótarján, Pf. 118.">Nógrád Volán Zrt.</option>
            <option value="7622 Pécs, Siklósi u. 1.">Pannon Volán Zrt.</option>
            <option value="7620 Pécs, Pf. 111.">Pécsi Közlekedési Zrt.</option>
            <option value="8400 Ajka, Hársfa u. 7.">Somló Volán Zrt.</option>
            <option value="4401 Nyíregyháza, Pf. 51.">Szabolcs Volán Zrt.</option>
            <option value="6720 Szeged, Zrínyi u. 4-8.">Szegedi Közlekedési Kft.</option>
            <option value="6701 Szeged, Pf. 185.">Tisza Volán Zrt.</option>
            <option value="9700 Szombathely, Körmendi u. 92.">Vasi Volán Zrt.</option>
            <option value="2800 Tatabánya, Csaba u. 19.">Vértes Volán Zrt.</option>
            <option value="1395 Budapest, Pf. 407.">Volánbusz Zrt.</option>
            <option value="8900 Zalaegerszeg, Gasparich Márk u. 16.">Zala Volán Zrt.</option>
            <option value="1104 Budapest, Mádi u. 28-38.">Weekendbus Zrt.</option>
            </select> (a nyugtán megtalálható)
        </td>

    </tr><tr>
        <td class="vertical-spacing"></td><td></td>
    </tr><tr>
        <td class="form-row-title">A hivatalos neved:</td><td><input type="text" id="person-name" /></td>
    </tr><tr>
        <td class="form-row-title">Lakhelyed:</td><td><input type="text" id="person-location" /> (a településnév, ami levél datálásához kell)</td>

    </tr><tr>
        <td class="vertical-spacing"></td><td></td>
    </tr><tr>
        <td class="form-row-title">Indulás helye:</td>
        <td><input type="text" id="start-location" /></td>
    </tr><tr>
        <td class="form-row-title">Indulás ideje:</td>
        <td>
            <?php draw_select('start-year', 2, date('Y')-1, date('Y')); ?> év&nbsp;
            <?php draw_select('start-month', 12, 0, date('n')-1, $month_names); ?> hónap&nbsp;
            <?php draw_select('start-day', 31, 1, date('j')); ?> nap&nbsp;
            <?php draw_select('start-hour', 24); ?> óra&nbsp;
            <?php draw_select('start-minute', 60); ?> perc
        </td>
    </tr><tr>
       <td class="vertical-spacing"></td><td></td>

    </tr><tr>
        <td class="form-row-title">Érkezés helye:</td>
        <td><input type="text" id="end-location" /></td>
    </tr><tr>
        <td class="form-row-title">Érkezés ideje:</td>
        <td>
            <?php draw_select('end-hour', 24); ?> óra&nbsp;
            <?php draw_select('end-minute', 60); ?> perc
        </td>
    </tr>
</table>

<h3>Opcionális adatok (minél többet ajánlott megadni)</h3>

<table>
    <tr>
        <td class="form-row-title">Busz száma:</td><td><input type="text" id="bus-number" /> (helyi járat esetén)</td>
    </tr><tr>
        <td class="form-row-title">Busz rendszáma:</td><td><input type="text" id="license-plate-number" /></td>
    </tr>

    <tr>
        <td class="vertical-spacing"></td><td></td>
    </tr><tr>
        <td class="form-row-title">1. tanú neve:</td><td><input type="text" id="witness1-name" /></td>
    </tr><tr>
        <td class="form-row-title">1. tanú anyja neve:</td><td><input type="text" id="witness1-mothers-name" /></td>
    </tr><tr>
        <td class="form-row-title">1. tanú szem. ig. száma:</td><td><input type="text" id="witness1-id" /></td>
    </tr><tr>
        <td class="form-row-title">1. tanú címe:</td><td><input type="text" id="witness1-address" size="40" /></td>
    </tr><tr>
        <td class="vertical-spacing"></td><td></td>
    </tr><tr>
        <td class="form-row-title">2. tanú neve:</td><td><input type="text" id="witness2-name" /></td>
    </tr><tr>
        <td class="form-row-title">2. tanú anyja neve:</td><td><input type="text" id="witness2-mothers-name" /></td>
    </tr><tr>
        <td class="form-row-title">2. tanú szem. ig. száma:</td><td><input type="text" id="witness2-id" /></td>
    </tr><tr>
        <td class="form-row-title">2. tanú címe:</td><td><input type="text" id="witness2-address" size="40" /></td>
    </tr>
</table>

<h3>Levél generálása</h3>

<table>
    <tr>
        <td></td>
        <td><b><span id="error-message" class="alert" style="text-align:left; display:none"></span></b></td>
    </tr><tr>
        <td class="form-row-title"></td>
        <td><input type="button" id="generate-mail-button" value="Levél generálása a megadott adatok alapján" /></td>
    </tr>
</table>

<h2>2. Levél további szerkesztése</h2>

<p>Az előző pontban generált levelet kiegészítheted ha jónak látod, de persze csak kultúráltan tedd azt.  Azt mindenképpen említsd meg ha a sofőr nem volt hajlandó elnyomni a cigit.</p>

<textarea id="mail-editor" rows="15" cols="80"></textarea>

<h2>3. Levél kinyomtatása</h2>

<p>Az alábbi gombok valamelyikére történő kattintással megnyithatod a fenti levelet a kívánt formátumban és könnyen kinyomtathatod.</p>

<form method="post" action="">
<input type="hidden" name="text" />
<table>
    <tr><td><input type="button" id="pdf" value="Portable Document Format (.PDF)" /></td></tr>
    <tr><td><input type="button" id="doc" value="Microsoft Word (.DOC)" /></td></tr>
</table>
</form>

<h2>4. Levél feladása</h2>

<p>Fontos, hogy a levelet <b>ajánlottan</b> add fel, ugyanis így nyoma marad, hogy átvették és nem hivatkozhatnak rá, hogy nem kapták meg.</p>

<p>Az alábbi boritékon szerepel a fentiekben kiválasztott közlekedési társaság címe.</p>

<div id="envelope">
<table style="width:100%; height:100%">
    <tr style="height:50%">
        <td style="width:40%">
            <span id="envelope-person-name"></span><br />
            <span id="envelope-person-location"></span><br />
            <i>utcád, házszámod</i><br />
            <b><i>irányítószámod</i></b><br />
        </td>
        <td style="width:60%"></td>
    </tr><tr style="height:50%">
        <td></td>
        <td id="envelope-address"></td>
    </tr>
</table>
</div>

<h1>Nem kaptál választ tőlük?</h1>

<p>A közlekedési társaságoknak közszolgáltatóként kötelező lenne válaszolniuk, de ha nem kapsz tőlük választ akkor keress meg engem és utánajárok, hogy milyen lépések lehetségesek.</p>

<h1>Adj hírt magadról!</h1>

<p>Téged is frusztrálnak a füstös buszok?  Megmondtad már a frankót egy sofőrnek vagy küldtél már levelet valamelyik társaságnak?  Esetleg találtál valamilyen pontatlanságot ezen az oldalon?</p>

<p>Szívesen hallanék rólad!</p>

<p>A <span id="email">&lt;laci kukac monda pont hu&gt;</span> címen elérhetsz.</p>

<h1>Felelősségvállalás</h1>

<p>Ezzel a szájttal egy társadalmi problémának akarok véget vetni és minden tőlem telhetőt megtettem annak érdekében, hogy ehhez hatékonyan hozzásegítsek mindenkit, de nem vállalok felelősséget az itt közzétett információk korrektségéért és a felhasználásukból adódó következményekért.</p>

<h1>Adatvédelmi politika</h1>

<p>Tiszteletben tartom a privát szférádat, az ezen a szájton megadott adatokat semmilyen formában nem rögzítem.  Egyedül a Google Analytics szolgáltatását használom fel arra, hogy statisztikai információkat gyűjtsek be a látogatókról és semmi mást.</p>

<h1>Köszönetnyilvánítás</h1>

<p>Mérhetetlen köszönet illeti Visnyei László jóbarátomat a szájt jogi aspektusaiban nyújtott nélkülözhetetlen segítségéért, és köszönöm Kószó József barátomnak is a visszajelzéseket.</p>

<p>Köszönöm <a href="http://furedi.hu/" target="_blank">Füredi Krisztiánnak</a>, hogy belinkelte az ezt az oldalt a <a href="http://dohanyzas.lap.hu" target="_blank">dohanyzas.lap.hu</a> oldalra.</p>

<p>Legvégül pedig köszönöm az érintett buszsofőröknek, hogy addig húzták az agyamat, amíg össze nem raktam ezt a szájtot.  :)</p>

</div>
&copy; 2008 Monda László.  <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Néhány jog fenntartva.</a>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6307134-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
