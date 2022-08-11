import $ from 'jquery';

class Search {
    // 1. Constructor describe and create/initiate our object
    constructor() {
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay"); 
        this.searchField = $("search-term");
        this.typingTimer;  // En property som används i funktionen typingLogic
        this.events();  // Pekar på våra events
        this.isOverlayOpen = false;  // Kontrollerar om sökrutan redan är öppen eller inte.
    }

    // 2. Events, happenings
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keydown", this.typingLogic.bind(this));
    }
    
    // 3. Methods (function, logic, action...)

    typingLogic() { // En funktion för att skicka en förfrågan till databasen på resultat av varje knapptryck som görs.
        clearTimeout(this.typingTimer); // Nollställer Propertyn typingTimer varje gång funktionen anropas för att inte anropen ska göras för varje knapptryck.
        this.typingTimer = setTimeout(function () {console.log("This is a test");}, 2000);  // Skickar endast en förfrågan om man pausar knapptryckningarna 2 sekunder.
    }

    keyPressDispatcher(e) {  /* En funktion som öppnar sökrutan om tangentbordsknappen S blir tryckt och sökrutan stängs om ESCAPE blir tryckt.  */
    if (e.keyCode == 83 && !this.isOverlayOpen) {  /* keyCode == 83 => 83 är siffran S  this.isOverlayOpen kontrollerar om sökrutan redan är öppen. */
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.isOverlayOpen) {   /* nummer 27 är ESC knappen this.isOverlayOpen kontrollerar om sökrutan redan är stängd.*/
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("Body-no-scroll"); // Gör så man inte kan skrolla på sidan när sökfunktionen är aktiverad.
        this.isOverlayOpen = true;  // Ändrar propertyn till true, håller man in knappen ska den inte skicka en förfrågan igen och igen...
    }
    
    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("Body-no-scroll"); /* Gör så man kan skrolla igen på sidan när sökfunktionen är avaktiverad. */
        this.isOverlayOpen = false;     // Ändrar tillbaka propertyn till false för att inte skicka förfrågan flera ggr om knappen hålls intryckt.
    }
}

export default Search;