export async function getCrawl(id) {
    //Send request to backend (json for bars table appears in console) 
    const response = await fetch(`./getCrawl.php?id=${id}`, { method: "GET" });

    return await response.json();
}