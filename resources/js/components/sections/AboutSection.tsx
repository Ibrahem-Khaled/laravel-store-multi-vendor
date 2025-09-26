import ProductCarousel from "../About/ProductCarousel";
import About from "../About/About";
import BlogSection from "../About/BlogSection";

const AboutSection = () => {
  return (
    <div className="bg-white flex flex-col justify-start items-center overflow-x-clip">
      <ProductCarousel />
      <div className="mt-[40px] sm:mt-20 w-full md:w-[80vw] bg-[#191B1D] md:rounded-4xl ">
        <About />
      </div>
        <BlogSection />
    </div>
  );
};


export default AboutSection



