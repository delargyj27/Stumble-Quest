export async function getAllBars() {
  //Send request to backend (json for bars table appears in console)
  const response = await fetch(`./getAllBars.php`, {
    method: "GET",
    credentials: "include",
  });

  // If error status code...
  if (!response.ok) {
    throw new Error("Something went wrong.");
  }

  return await response.json();
}
