import { gsap } from "./gsap-core.js";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

window.ScrollTrigger = ScrollTrigger;

export { ScrollTrigger };
