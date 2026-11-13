const postcss = require("postcss");
const plugin = require("./postcss/isolate-legacy-important.cjs");

async function test(input) {
  const r = await postcss([plugin()]).process(input, { from: undefined });
  console.log("INPUT :", JSON.stringify(input));
  console.log("OUTPUT:", JSON.stringify(r.css));
  console.log("---");
}

(async () => {
  await test(".leaflet-popup a, .x a{color:blue}");
  await test(".leaflet-pane{position:absolute;z-index:400}");
  await test(".legacy a{color:red}");
  await test(".x a{color:blue}");
})();
