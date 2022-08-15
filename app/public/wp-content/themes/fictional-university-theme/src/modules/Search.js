import $ from 'jquery';

class Search {
    // 1. Constructor describe and create/initiate our object
    constructor() {
        this.resultsDiv = $("#search-overlay__results")    // En property som refererar till id:t på en div i footer.php för att rendera sökresultat.
        this.openButton = $(".js-search-trigger")
        this.closeButton = $(".search-overlay__close")
        this.searchOverlay = $(".search-overlay")
        this.searchField = $("#search-term")
        this.typingTimer  // En property som används i funktionen typingLogic
        this.events()  // Pekar på våra events
        this.isOverlayOpen = false  // Kontrollerar om sökrutan redan är öppen eller inte.
        this.isSpinnerVisible = false  // Sätter laddar ikonen att vara osynlig tills man börjat skriva något i sökrutan.
        this.previousValue  // Håller reda på vilken knapp som trycktes ner senast.
    }

    // 2. Events, happenings
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }
    
    // 3. Methods (function, logic, action...)
    typingLogic() { // En funktion för att skicka en förfrågan till databasen på resultat av varje knapptryck som görs.
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer); // Nollställer Propertyn typingTimer varje gång funktionen anropas för att inte anropen ska göras för varje knapptryck.

            if (this.searchField.val()) {  // Om sökfältet är tomt
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;  // Får en laddar ikon bli synlig medan man väntar på sökresultat.
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000);  // Skickar endast en förfrågan om man pausar knapptryckningarna 2 sekunder.
            }
            else {
                this.resultsDiv.html('');   // Om man raderar det man skrivit i sökfältet ska inte laddarikonen dyka upp.
                this.isSpinnerVisible = false;
            }

        }
        this.previousValue = this.searchField.val();  // Propertyn previousValue får värdet från searchField som är inputfältet till sökrutan. 
    }

    getResults () {
        this.resultsDiv.html("Imagine real results here!");        
        this.isSpinnerVisible = false;  // Får laddar ikonen bli osynlig igen när resultatet har presenterats.
    }

    keyPressDispatcher(e) {  /* En funktion som öppnar sökrutan om tangentbordsknappen S blir tryckt och sökrutan stängs om ESCAPE blir tryckt.  */
    /* keyCode == 83 => 83 är siffran S  this.isOverlayOpen kontrollerar om sökrutan redan är öppen. 
    !$("input, textarea".is(':focus')) kollar om ett annat inputfält är i fokus så man kan skriva bokstaven S utan att öppna sökrutan.*/
    if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {  
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.isOverlayOpen) {   /* nummer 27 är ESC knappen this.isOverlayOpen kontrollerar om sökrutan redan är stängd.*/
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass("body-no-scroll"); // Gör så man inte kan skrolla på sidan när sökfunktionen är aktiverad.
        this.isOverlayOpen = true;  // Ändrar propertyn till true, håller man in knappen ska den inte skicka en förfrågan igen och igen...
    }
    
    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll"); /* Gör så man kan skrolla igen på sidan när sökfunktionen är avaktiverad. */
        this.isOverlayOpen = false;     // Ändrar tillbaka propertyn till false för att inte skicka förfrågan flera ggr om knappen hålls intryckt.
    }
}
export default Search;