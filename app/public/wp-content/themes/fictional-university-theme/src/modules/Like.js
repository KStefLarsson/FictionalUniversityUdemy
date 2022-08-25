import $ from 'jquery';

class Like {
    constructor() {
        
    }

    events() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    // Methods
    ourClickDispatcher() {
        
    }
}

export default Like;