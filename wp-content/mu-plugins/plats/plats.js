document.addEventListener('DOMContentLoaded', () => {



    let date = document.querySelector('[data-name="date_plat"] input[type=hidden]')

    if (date) {

        setInterval(() => {
            if (date.dataset.value != date.value) {
                let newDate
                if (newDate = formatDateToFrench(date.value)) {
                    document.querySelector('#title').value = newDate;
                }
            }
            date.dataset.value = date.value;
        }, 500);
    }

    let champs = document.querySelectorAll('#acf-group_64febc001bd27 input[type=text]');

    if (champs) {
        champs.forEach(champ => champ.addEventListener('input', (e) => {
            construireTexte();
        }))
        window.addEventListener("load", (event) => {
            construireTexte();
        });
    }

    function construireTexte() {
        let texte = [];

        let plat_viande = document.querySelector('[data-name="plat_viande"] input').value;
        let accompagnement_viande = document.querySelector('[data-name="accompagnement_viande"] input').value;
        let plat_vege = document.querySelector('[data-name="plat_vege"] input').value;
        let accompagnement_vege = document.querySelector('[data-name="accompagnement_vege"] input').value;
        let specialite = document.querySelector('[data-name="specialite"] input').value;
        let specialite_vege = document.querySelector('[data-name="specialite_vege"] input').value;
        let desserts = document.querySelector('[data-name="desserts"] input').value;
        if (plat_viande) {
            texte.push('<strong class="plat" id="plat_viande">' + plat_viande + '</strong>');
        }
        if (accompagnement_viande) {
            texte.push('<p class="accompagnement" id="accompagnement_viande">' + accompagnement_viande + '</p>');
        } else texte.push('<br>')
        if (plat_vege) {
            texte.push('<strong class="plat" id="plat_vege">' + plat_vege + '</strong>');
        }
        if (accompagnement_vege) {
            texte.push('<p class="accompagnement" id="accompagnement_vege">' + accompagnement_vege + '</p>');
        } else texte.push('<br>')
        texte.push('<p class="specialite" id="specialite">');
        if (specialite) {
            texte.push(specialite + '<br>');
        }
        if (specialite_vege) {
            texte.push(specialite_vege + '<br>');
        }
        texte.push('</p>');
        if (desserts) {
            texte.push('<p id="desserts">' + desserts + '</p>');
        }
        if (tinymce && texte.length) {
            tinymce.get('excerpt').setContent(texte.join('\n'));
        }
        // document.querySelector('#excerpt').value = texte.join('\n');
    }
    function formatDateToFrench(dateString) {
        if (!dateString) return '';
        const monthsInFrench = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];

        const year = dateString.substring(0, 4);
        const month = dateString.substring(4, 6);
        const day = dateString.substring(6, 8);

        return `${parseInt(day, 10)} ${monthsInFrench[parseInt(month, 10) - 1]} ${year}`;
    }
})