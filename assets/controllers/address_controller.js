import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [ 'input', 'coordinates', 'plus' ];

    search() {
        let addressWords = this.inputTarget.value.split(' ');
        let addressPlus = addressWords.join('+');

        fetch(
            'https://api-adresse.data.gouv.fr/search/?q=' + addressPlus,
        )
        .then((response) => response.json())
        .then((data) => {
            let latitude = data.features[0].geometry.coordinates[1];
            let longitude = data.features[0].geometry.coordinates[0];

        
            let searchResult = 'Latitude : ' + latitude + ' // Longitude : ' + longitude;
            this.coordinatesTarget.textContent = searchResult;

            setTimeout(() => {
                fetch(
                    '/address/register/' + latitude + '/' + longitude,
                )
                .then((response) => {
                    response.json();
                })
            }, 4000);
        })
        // .then(
        //     fetch(
        //         '/address/' + lat + '/' + lng,
        //     )
        // )

    }

}