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
console.log(id, field_value);
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
    $('select.generated').each(function(selectId, selectElement) {
        var select = $(selectElement);
        var numberOfItems = select.data('numberOfItems');

        var startNumber = select.data('startNumber');
        if (startNumber === undefined) {
            startNumber = 0;
        }

        var defaultNumber = select.data('defaultNumber');
        var options = null;
        switch (select.attr('id')) {
            case 'start-year':
                defaultNumber = new Date().getFullYear();
                startNumber = defaultNumber - 1;
                break;
            case 'start-month':
                defaultNumber = new Date().getMonth();
                options = ['január', 'február', 'március', 'április', 'május', 'június',
                           'július', 'augusztus', 'szeptember', 'október', 'november', 'december'];
                break;
            case 'start-day':
                defaultNumber = new Date().getDate();
                break;
        }

        select.append($("<option default></option>"));
        for (var i=0; i<numberOfItems; i++) {
            var value = null;
            var text = null;
            if (options) {
                value = i;
                text = options[i];
            } else {
                value = i+startNumber;
                value = text = value<10 ? "0"+value : value;
            }
            select.append($("<option></option>").attr("value", value).text(text));
        }

        select.val(defaultNumber);
    });

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
