import $ from 'jquery';

class Search {
    // 1. Constructor describe and create/initiate our object
    constructor() {
        this.addSearchHTML();
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
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);  // Skickar endast en förfrågan om man pausar knapptryckningarna efter angivna millisekundrar.
            }
            else {
                this.resultsDiv.html('');   // Om man raderar det man skrivit i sökfältet ska inte laddarikonen dyka upp.
                this.isSpinnerVisible = false;
            }

        }
        this.previousValue = this.searchField.val();  // Propertyn previousValue får värdet från searchField som är inputfältet till sökrutan. 
    }

    /* Rest API
       En funktion som renderar sökresultat utifrån användarens input. 
       .html renderar html koden.
       Allt som skrivs inom backticks gör till ren text, medan kod som skriivs inom ${} gör så wordpress läser det till javascript
       Med .map() loopar vi igenom hela posts arrayen och gör om item i arrayen till en länk. 
       .join bestämmer om/vad man ska ha mellan alla items i arrayen, ett komma är default i detta fall ('') blir det inget mellan. 
    */
    getResults() {
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
            this.resultsDiv.html(`
            <div class="row">
                <div class="one-third">
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                    ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a>${item.postType == 'post' ? ` by ${item.authorName}` : ''}</li>`).join('')}
                    ${results.generalInfo.length ? '</ul>' : ''}
                </div>

                <div class="one-third">
                    <h2 class="search-overlay__section-title">Programs</h2>
                    ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`}
                    ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                    ${results.programs.length ? '</ul>' : ''}

                    <h2 class="search-overlay__section-title">Professors</h2>
                
                </div>
                <div class="one-third">
                    <h2 class="search-overlay__section-title">Campuses</h2>
                    ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`}
                    ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                    ${results.campuses.length ? '</ul>' : ''}

                    <h2 class="search-overlay__section-title">Events</h2>
                
                </div>
            </div>
            `);
            this.isSpinnerVisible = false;
        });
    }

    /* En funktion som öppnar sökrutan om tangentbordsknappen S blir tryckt och sökrutan stängs om ESCAPE blir tryckt.  
       keyCode == 83 => 83 är siffran S, this.isOverlayOpen kontrollerar om sökrutan redan är öppen. 
       !$("input, textarea".is(':focus')) kollar om ett annat inputfält är i fokus så man kan skriva bokstaven S utan att öppna sökrutan.
    */
    keyPressDispatcher(e) {  
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
        this.searchField.val('');  // Gör inputfältet tomt varje gång man öppnar sökrutan.
        setTimeout(() => this.searchField.focus(), 301);  // Sätter markören automatiskt i sökrutan med en fördröjning så sidan hunnit ladda helt.
        this.isOverlayOpen = true;  // Ändrar propertyn till true, håller man in knappen ska den inte skicka en förfrågan igen och igen...
    }
    
    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll"); /* Gör så man kan skrolla igen på sidan när sökfunktionen är avaktiverad. */
        this.isOverlayOpen = false;     // Ändrar tillbaka propertyn till false för att inte skicka förfrågan flera ggr om knappen hålls intryckt.
    }

    // Denna html renderar sökresultatet på sidan.
    addSearchHTML() {
        $("body").append(`
        <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results">
                 
          </div>
        </div>
      </div>
        `)
    }

}
export default Search;