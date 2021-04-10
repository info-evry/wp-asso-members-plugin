    const adieve = {};

    adieve.form = document.getElementById('adieve-form');

if ( adieve.form ) {

    adieve.form.addEventListener('submit', function (event) {

        event.stopPropagation();
        event.preventDefault();

        if (!adieve_globals) {
            throw new Error('adieve_globals is undefined.');
        }

        const {
            target: {
                ['adieve-firstname']: { value: a },
                ['adieve-lastname']: { value: b },
                ['adieve-email']: { value: c },
                ['adieve-phone']: { value: d },
                ['adieve-student']: { value: h },
                ['adieve-studlevel']: { value: i },
            }
        } = event;

        const {
            adieve_ajax: e,
            adieve_members_action: f,
            adieve_members_nonce: g
        } = adieve_globals;

        ecs_fetch({
            type: 'POST',
            url: e,
            data: {
                adieve_firstname: a,
                adieve_lastname: b,
                adieve_email: c,
                adieve_phone: d,
                adieve_student: h,
                adieve_studlevel: i,
                action: f,
                adieve_members_nonce: g,
            },
            success: data => data,
            error: data => data,
            jsonify: true,
            callback: data => data
        }).then(data => {
            if ( data && data === '1' || data === 1 ) {
                adieve.form.innerHTML = '<span>Votre demande d\'inscription a été prise en compte. Nous vous recontacterons rapidement.</span>';
            }
            else {
                adieve.form.innerHTML = '<span>Votre demande d\'inscription n\'a pas pu être prise en compte. <a href="/contact/">Contactez nous pour avoir plus d\'informations.</a></span>';
            }
        });

    }, false);

}