import Footer from '../components/Footer'
import Navbar from '../components/Navbar'
import FeaturesSection from '../components/sections/FeaturesSection'
import CommentSection from '../components/sections/CommentSection'
import HeroSection from '../components/sections/HeroSection'
import ProductSection from '../components/sections/ProductSection'

function TechStoreLanding() {
  return (
    <div className='min-h-screen w-full bg-white'>
      <Navbar />
      <HeroSection />
      <FeaturesSection />
      <ProductSection />
      <CommentSection />
      <Footer />
    </div>
  )
}

export default TechStoreLanding
