\nconst postcss = require("postcss");
\nconst plugin = require("./postcss/isolate-legacy-important.cjs");
\nconst tests = [
\n  ".leaflet-popup a, .x a{color:blue}",
\n  ".leaflet-pane{position:absolute;z-index:400}",
\n  ".legacy a{color:red}",
\n  ".x a{color:blue}",
\n  ".marker-cluster div{width:30px}",
\n  ".map-picker-leaflet-pane{color:red}",
\n];
\n(async () => {
\n  for (const input of tests) {
\n    const r = await postcss([plugin()]).process(input, { from: undefined });
\n    console.log("INPUT :", JSON.stringify(input));
\n    console.log("OUTPUT:", JSON.stringify(r.css));
\n    console.log("---");
\n  }
\n})();
