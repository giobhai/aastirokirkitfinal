export default async function handler(req, res) {
  // Retrieve the relative path from the query string (e.g. "2504/default_primary.mpd")
  const { get } = req.query;
  if (!get) {
    return res.status(400).send('Missing required parameter "get"');
  }

  // Construct the target MPD URL
  const targetUrl = `https://linearjitp-playback.astro.com.my/dash-wv/linear/${get}`;

  try {
    // Fetch the MPD file using a custom User-Agent header
    const response = await fetch(targetUrl, {
      headers: {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36"
      }
    });

    if (!response.ok) {
      return res.status(response.status).send("Error fetching MPD");
    }

    const text = await response.text();
    // Set appropriate Content-Type for MPD (DASH)
    res.setHeader("Content-Type", "application/dash+xml");
    return res.status(200).send(text);
  } catch (err) {
    return res.status(500).send("Server error");
  }
}
