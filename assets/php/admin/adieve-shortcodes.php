<?php

if (!function_exists('adieve_shortcode_init')) {
    function adieve_shortcode_init()
    {
        function adieve_shortcode_members()
        {

            $label_firstname = esc_html__("Prénom", 'adieve');
            $input_firstname = esc_attr__("Prénom", 'adieve');

            $label_lastname = esc_html__("Nom", 'adieve');
            $input_lastname = esc_attr__("Nom", 'adieve');

            $label_email = esc_html__("Email", 'adieve');
            $input_email = esc_attr__("Email", 'adieve');

            $label_phone = esc_html__("Téléphone", 'adieve');
            $input_phone = esc_attr__("+336 12 34 56 78", 'adieve');

            $label_student = esc_html__("Numéro d'Etudiant", 'adieve');
            $input_student = esc_attr__("201XXXXX", 'adieve');

            $label_studlevel = esc_html__("Filière", 'adieve');
            $input_studlevel = esc_attr__("L3 INF...", 'adieve');

            $cta_text = esc_attr__("S'inscrire à l'ADIEVE", 'adieve');

            return "<div class=\"adieve-form\"\>" .
                "<form id=\"adieve-form\" method=\"post\" accept-charset=\"utf-8\" autocomplete=\"on\">" .
                "<div>" .
                "<div>" .
                "<label for=\"adieve-firstname\" id=\"adieve-firstname-label\">{$label_firstname}</label>" .
                "<input type=\"text\" id=\"adieve-firstname\" name=\"adieve-firstname\" placeholder=\"{$input_firstname}\" aria-placeholder=\"{$input_firstname}\" autocomplete=\"given-name\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\"></p>".
                "</div>" .
                "<div>" .
                "<label for=\"adieve-lastname\" id=\"adieve-lastname-label\">{$label_lastname}</label>" .
                "<input type=\"text\" id=\"adieve-lastname\" name=\"adieve-lastname\" placeholder=\"{$input_lastname}\" aria-placeholder=\"{$input_lastname}\" autocomplete=\"family-name\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\"></p>".
                "</div>" .
                "</div>" .
                "<div>" .
                "<div>" .
                "<label for=\"adieve-email\" id=\"adieve-email-label\">{$label_email}</label>" .
                "<input type=\"email\" id=\"adieve-email\" name=\"adieve-email\" placeholder=\"{$input_email}\" aria-placeholder=\"{$input_email}\" autocomplete=\"email\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\"></p>".
                "</div>" .
                "<div>" .
                "<label for=\"adieve-phone\" id=\"adieve-phone-label\">{$label_phone}</label>" .
                "<input type=\"tel\" id=\"adieve-phone\" name=\"adieve-phone\" placeholder=\"{$input_phone}\" aria-placeholder=\"{$input_phone}\" autocomplete=\"tel\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\">Votre numéro de téléphone ne sera utilisé que pour vous ajouter au groupe de discussion de l'association.</p>".
                "</div>" .
                "</div>" .
                "<div>" .
                "<div>" .
                "<label for=\"adieve-student\" id=\"adieve-student-label\">{$label_student}</label>" .
                "<input type=\"text\" id=\"adieve-student\" name=\"adieve-student\" placeholder=\"{$input_student}\" aria-placeholder=\"{$input_student}\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\">Votre numéro d'étudiant nous permet de vous retrouver d'un point de vue administratif et de vérifier votre statut d'étudiant au sein de l'université. Il vous servira à l'avenir comme moyen d'identification pour publier du contenu sur ce site.</p>".
                "</div>" .
                "<div>" .
                "<label for=\"adieve-studlevel\" id=\"adieve-studlevel-label\">{$label_studlevel}</label>" .
                "<input type=\"text\" id=\"adieve-studlevel\" name=\"adieve-studlevel\" placeholder=\"{$input_studlevel}\" aria-placeholder=\"{$input_studlevel}\" required aria-required=\"true\" autocorrect />" .
				"<p class=\"input-detail\"></p>".
                "</div>" .
                "</div>" .
                "<div>" .
                "<input type=\"submit\" id=\"adieve-submit\" aria-disabled=\"true\" role=\"button\" value=\"{$cta_text}\" />" .
                "</div>" .
                "</form>" .
                "</div>";
        }
        add_shortcode('adieve_members', 'adieve_shortcode_members');
    }
}
add_action('init', 'adieve_shortcode_init');
