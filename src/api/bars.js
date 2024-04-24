export async function getAllBars() {
    //Send request to backend (json for bars table appears in console) 
    const response = await fetch(`./getAllBars.php`, { method: "GET" });

    return await response.json();
}