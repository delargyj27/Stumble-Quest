export async function getCrawl(id) {
  //Send request to backend (json for selected data from crawls table appears in console)
  const response = await fetch(`./getCrawl.php?id=${id}`, {
    method: "GET",
    credentials: "include",
  });

  // If error status code...
  if (!response.ok) {
    throw new Error("Something went wrong.");
  }

  return await response.json();
}

export async function addBarToCrawl(barId, crawlId) {
  const response = await fetch(`./addBarToCrawl.php`, {
    method: "POST",
    body: JSON.stringify({ barId: barId, crawlId: crawlId }),
    credentials: "include",
  });

  // If error status code...
  if (!response.ok) {
    throw new Error("Something went wrong.");
  }

  return await response.json();
}

export async function clearCrawlBars(crawlId) {
  const response = await fetch(`./clearCrawlBars.php`, {
    method: "POST",
    body: JSON.stringify({ crawlId: crawlId }),
    credentials: "include",
  });

  // If error status code...
  if (!response.ok) {
    throw new Error("Something went wrong.");
  }

  return await response.json();
}
