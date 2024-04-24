export async function getAllEvents() {
    //Send request to backend (json for events table appears in console) 
    const response = await fetch(`./getAllEvents.php`, { method: "GET" });

    return await response.json();
}