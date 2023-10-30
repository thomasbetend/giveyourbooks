import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = [
        'autocomplete', 'latitude', 'longitude'
    ];

    connect() {
      new window.Autocomplete(this.autocompleteTarget.id, {
        selectFirst: true,
        insertToInput: true,
        cache: true,
        howManyCharacters: 2,
        // onSearch
        onSearch: ({ currentValue }) => {
          // api
          const api = `https://nominatim.openstreetmap.org/search?format=geojson&limit=10&q=${encodeURI(
            currentValue + ' France'
          )}`;
      
          return new Promise((resolve) => {
            fetch(api)
              .then((response) => response.json())
              .then((data) => {
                resolve(data.features);
              })
              .catch((error) => {
                console.error(error);
              });
          });
        },
      
        // nominatim GeoJSON format
        onResults: ({ currentValue, matches, template }) => {
          const regex = new RegExp(currentValue, "gi");
      
          // if the result returns 0 we
          // show the no results element
          return matches === 0
            ? template
            : matches
                .map((element) => {
                  return `
                      <li>
                        <p>
                          ${element.properties.display_name.replace(
                            regex,
                            (str) => `<b>${str}</b>`
                          )}
                        </p>
                      </li> `;
                })
                .join("");
        },
      
        onSubmit: ({ object }) => {
            let longitude = object.geometry.coordinates[0];
            let latitude = object.geometry.coordinates[1];

            this.latitudeTarget.value = latitude;
            this.longitudeTarget.value = longitude;
        },
      
        noResults: ({ currentValue, template }) =>
          template(`<li>No results found: "${currentValue}"</li>`),
      });
    }
}
