/**
 * Create and return an HTML element given an HTML string.
 * 
 * @example
 * const button = html(`<button></button>`);
 * const div = html(`<div></div>`);
 */
export function html(htmlString) {
    const template = document.createElement("template");
    template.innerHTML = htmlString.trim(); //trim whitespace from string to display
    return template.content.firstChild;
}