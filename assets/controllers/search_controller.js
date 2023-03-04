import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [ 'input', 'coordinates' ];

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
            let city = data.features[0].properties.city;

        
            let searchResult = 'Latitude : ' + latitude + ' // Longitude : ' + longitude + ' // à ' + city;
            this.coordinatesTarget.textContent = searchResult;

            fetch(
                    '/address/register/' + latitude + '/' + longitude + '/' + city,
            )
            .then((response) => {
                response.json();
            })
        })

    }

}