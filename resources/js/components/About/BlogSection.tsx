import { motion } from "framer-motion";

const BlogCard: React.FC<{ blog: Blog; index: number }> = ({ blog, index }) => {
  return (
    <div
      className="
        relative rounded-xl overflow-hidden
        min-w-[50%] sm:min-w-[300px] md:min-w-0
        h-[350px] snap-center
        shadow-lg hover:shadow-xl transition-all duration-300 shadow-black/30
        z-20
      "
    >
      {/* STATIC overlay / frosted background — DO NOT animate this element */}
      <div
        className="absolute inset-0 z-0 pointer-events-none rounded-xl"
        style={{
          // visible semi-transparent fallback + backdrop blur
          background:
            "linear-gradient(180deg, rgba(17,24,39,0.35) 0%, rgba(17,24,39,0.25) 100%)",
          backdropFilter: "blur(4px)",
          WebkitBackdropFilter: "blur(4px)",
        }}
        aria-hidden
      />

      {/* Image section */}
      <div className="relative w-full h-32 overflow-hidden">
        <img
          src={blog.image}
          alt={blog.title}
          className="w-full h-full object-cover object-center"
        />
      </div>

      {/* CONTENT: animate only this wrapper (keeps overlay stable) */}
      <motion.div
        initial={{ opacity: 0, y: 18 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true, amount: 0.24 }}
        transition={{ delay: index * 0.06, duration: 0.45, ease: "easeOut" }}
        className="relative z-10 p-4 flex flex-col justify-between h-1/2"
      >
        <p className="text-gray-200 text-sm">{blog.date}</p>
        <h3 className="text-white text-base font-semibold mt-2 line-clamp-3">
          {blog.title}
        </h3>
        <button className="text-gray-200 text-sm mt-3 self-start bg-transparent">
          Read more
        </button>
      </motion.div>
    </div>
  );
};

const BlogSection: React.FC = () => {
  return (
    <section className="py-12 relative">
      {/* Section Header */}
      <div className="text-center mb-10 text-black">
        <h2 className="text-2xl md:text-3xl font-bold">OUR BLOG</h2>
        <p className="text-gray-600">Read the latest news and articles</p>
      </div>

      {/* Blog List */}
      <div className="overflow-x-auto md:overflow-visible no-scrollbar">
        <div
          className="
            flex md:grid md:grid-cols-4 gap-6 px-10 md:px-12
            snap-x snap-mandatory md:snap-none relative left-10 sm:left-0
          "
        >
          {blogs.map((blog, index) => (
            <BlogCard key={blog.id} blog={blog} index={index} />
          ))}
        </div>
      </div>

      {/* Read More Button */}
      <div className="w-full flex justify-center items-center pt-28 absolute z-20">
        <div className="bg-gradient-to-r from-orange-400 via-pink-500 to-blue-500 p-1 rounded-full hover:shadow-lg hover:shadow-pink-500/25 transition-all duration-300 w-fit">
          <button className="bg-gray-900 text-white px-10 py-3 rounded-full text-base font-normal hover:bg-gray-800 transition-colors">
            Read more
          </button>
        </div>
      </div>
    </section>
  );
};


export default BlogSection

type Blog = {
  id: string | number;
  title: string;
  date: string;
  image: string;
};

const blogs: Blog[] = [
  {
    id: 1,
    date: "Jun, 12, 2021",
    title: "Tech companies don’t get science fiction – and that's deeply troubling",
    image: "/apple-vision.png",
  },
  {
    id: 2,
    date: "Jun, 12, 2021",
    title: "These are the games to look out for in 2022",
    image: "/game-arm.png",
  },
  {
    id: 3,
    date: "Jun, 10, 2021",
    title: "Why Apple's crackdown on child abuse images is no easy decision",
    image: "/npc-random.png",
  },
  {
    id: 4,
    date: "Jun, 10, 2021",
    title: "The truth about the suspected link between social media and self-harm",
    image: "/apps.png",
  },
];