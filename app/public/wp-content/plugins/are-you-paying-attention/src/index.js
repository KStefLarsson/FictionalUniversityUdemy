import "./index.scss";
import {TextControl} from "@wordpress/components";


wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
    title: "Are You Paying Attention",
    icon: "smiley",
    category: "common",
    attributes: {
        skyColor: {type: "string"},
        grassColor: {type: "string"}
    },
    edit: EditComponent,
    save: function (props) {
        return (
            null
        )
    }
});

function EditComponent(props) {
    function updateSkyColor(event) {
        props.setAttributes({skyColor: event.target.value})
    }
    function updateGrassColor(event) {
        props.setAttributes({grassColor: event.target.value})
    }
    // Vi returnerar värdena med hjälp av JSX
    return (
        <div className="paying-attention-edit-block">
            <TextControl label="Question:"/>
            
        </div>    
    )
}
