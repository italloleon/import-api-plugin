/**
 * A class which control the breweries import
 */
class ImportBreweries {
    /**
     * Adds methods for ImportBreweries
     *
     * @returns {void}
     */
    constructor() {
        this.selectorButtonId = 'import-breweries';
        this.selectorListId = 'imported-breweries-list';
        this.addClickButtonEventListeners();
    }

    /**
     * Adds event handler for import button
     *
     * @returns {void}
     */
    addClickButtonEventListeners() {
        const self = this;
        let element = document.getElementById(this.selectorButtonId);
        try {
            element.addEventListener("click", self.requestAjaxDataBreweries.bind(self));
        } catch (error) {
            console.log(error);
        }
    }

    /**
     * Send data req to the backend
     *
     * @returns {void}
     */
    requestAjaxDataBreweries() {
        let self = this;
        let headers = new Headers();
        let myAjaxParams = {
            method: 'POST',
            credentials: 'same-origin',
            headers: headers,
            body: new URLSearchParams({
                action: 'import_breweries_from_json',
            })
        }
        self.toggleLoaderClass();
        self.disableButtonAfterStartApi();
        fetch(site_config_object.ajaxUrl, myAjaxParams)
            .then(response => response.json())
            .then(data => {
                self.mountBreweriesList(data);
            });
    }

    /**
     * Mount the breweries list with the ajax results
     *
     * @returns {void}
     */
    mountBreweriesList(arrayList) {
        let self = this;
        let elementsArray = arrayList;
        let ulElement = document.getElementById(self.selectorListId);
        elementsArray.forEach(element => {
            let elementName = element.brewery_name;
            let elementWpUrl = element.brewery_wp_url;
            let elementEditUrl = element.brewery_edit_url;
            let liText = self.createHtmlElement(
                'p',
                elementName,
                [],
                ['brewery-name']
            );
            let liElement = self.createHtmlElement(
                'li',
                '',
                [],
                ['brewery-item']
            );
            let buttonContainer = self.createHtmlElement(
                'div',
                '',
                [],
                ['button-container']
            );
            let checkButton = self.createHtmlElement(
                'a',
                'Check brewery',
                [
                    ['href', elementWpUrl],
                    ['target', '_blank']
                ],
                ['button', 'button-primary']);
            let editButton = self.createHtmlElement(
                'a',
                'Edit brewery',
                [
                    ['href', elementEditUrl],
                    ['target', '_blank']
                ],
                ['button', 'button-secondary']);
            liElement.append(liText);
            buttonContainer.append(checkButton);
            buttonContainer.append(editButton);
            liElement.append(buttonContainer);
            ulElement.append(liElement);
        });
        self.toggleLoaderClass();
    }

    /**
     * Creates a new Html object
     *
     * @returns {HTMLObjectElement}
     */
    createHtmlElement(elementString, text, attributes = [], classes = []) {
        let self = this;
        let elementToCreate = document.createElement(elementString);
        let elementAttributes = attributes;
        let elementClasses = classes;
        let elementText = text;
        elementToCreate.innerText = elementText;
        elementClasses.forEach(element => {
            elementToCreate.classList.add(element);
        });
        elementAttributes.forEach(element => {
            elementToCreate.setAttribute(element[0], element[1]);
        });
        return elementToCreate;
    }

    /**
     * Disable the button after the click
     *
     * @returns {void}
     */
    disableButtonAfterStartApi() {
        let self = this;
        let button = document.getElementById(self.selectorButtonId);
        button.setAttribute('disabled', 'disabled');
    }

    /**
     * Toggle the list loader class
     *
     * @returns {void}
     */
    toggleLoaderClass(){
        let self = this;
        let button = document.getElementById(self.selectorListId);
        button.classList.toggle('loading');
    };
}

new ImportBreweries();