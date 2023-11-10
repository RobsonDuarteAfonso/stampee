export default class AuctionService {

    #form;

    constructor() {
        
        if (AuctionService.instance == null) {
            AuctionService.instance = this;
        } else {
            console.error("Un seul AuctionService possible");
        }

        this.#form = document.querySelector("[data-js-from]");

        this.init();

    }

    init() {

        this.#form.addEventListener("submit", function(event) {

            event.preventDefault();

            this.#form.action = ''

        })
    }
}