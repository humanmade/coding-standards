const fs = require("fs");
const path = require("path");
const yaml = require("js-yaml");

const configPath = path.join(__dirname, ".eslintrc.yml");
module.exports = yaml.safeLoad(fs.readFileSync(configPath, "utf8"));
