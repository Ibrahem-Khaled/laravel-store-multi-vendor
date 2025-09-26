import { motion } from "framer-motion";

const ContactSection = () => {
  return (
    <section className="bg-white py-16 text-center z-30 inset-0 relative top-16">
      {/* Title */}
      <motion.h2
        initial={{ opacity: 0, y: 10 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        viewport={{ once: true }}
        className="text-3xl md:text-4xl font-extrabold text-black"
      >
        YOU HAVE QUESTIONS?
      </motion.h2>

      {/* Subtitle */}
      <motion.p
        initial={{ opacity: 0, y: 10 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: 0.1 }}
        viewport={{ once: true }}
        className="text-lg text-gray-800 mt-3 mb-6 font-medium"
      >
        Contact us
      </motion.p>

      {/* Contact Info */}
      <div className="space-y-4">
        <motion.div
          initial={{ opacity: 0, y: 10 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4, delay: 0.2 }}
          viewport={{ once: true }}
          className="flex justify-center items-center gap-3 text-gray-700"
        >
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 15.5C12.1421 15.5 15.5 12.1421 15.5 8C15.5 3.85786 12.1421 0.5 8 0.5C3.85786 0.5 0.5 3.85786 0.5 8C0.5 12.1421 3.85786 15.5 8 15.5Z" stroke="#131414" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span className="cursor-pointer hover:text-black transition">
            FAQ
          </span>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 10 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4, delay: 0.3 }}
          viewport={{ once: true }}
          className="flex justify-center items-center gap-3 text-gray-700"
        >
          <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 11.69V13.94C16.0009 14.1488 15.9581 14.3556 15.8744 14.547C15.7907 14.7383 15.668 14.9101 15.5141 15.0513C15.3601 15.1926 15.1784 15.3001 14.9806 15.367C14.7827 15.4339 14.573 15.4588 14.365 15.44C12.0571 15.1892 9.84025 14.4006 7.8925 13.1375C6.08037 11.9859 4.54401 10.4496 3.3925 8.63745C2.12499 6.68085 1.33619 4.4532 1.09 2.13495C1.07126 1.92755 1.09591 1.71852 1.16238 1.52117C1.22885 1.32382 1.33568 1.14247 1.47608 0.988666C1.61647 0.834865 1.78736 0.711983 1.97785 0.627842C2.16834 0.543702 2.37426 0.500147 2.5825 0.499951H4.8325C5.19648 0.496368 5.54935 0.62526 5.82532 0.8626C6.1013 1.09994 6.28156 1.42954 6.3325 1.78995C6.42747 2.51 6.60359 3.217 6.8575 3.89745C6.95841 4.16589 6.98025 4.45764 6.92043 4.73811C6.86062 5.01858 6.72165 5.27603 6.52 5.47995L5.5675 6.43245C6.63517 8.31011 8.18984 9.86478 10.0675 10.9325L11.02 9.97995C11.2239 9.7783 11.4814 9.63934 11.7618 9.57952C12.0423 9.5197 12.3341 9.54154 12.6025 9.64245C13.283 9.89636 13.99 10.0725 14.71 10.1675C15.0743 10.2188 15.4071 10.4024 15.6449 10.6831C15.8828 10.9638 16.0091 11.3221 16 11.69Z" stroke="#131414" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span>+999 000 000 000</span>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 10 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.4, delay: 0.4 }}
          viewport={{ once: true }}
          className="flex justify-center items-center gap-3 text-gray-700"
        >
          <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 1H14C14.825 1 15.5 1.675 15.5 2.5V11.5C15.5 12.325 14.825 13 14 13H2C1.175 13 0.5 12.325 0.5 11.5V2.5C0.5 1.675 1.175 1 2 1Z" stroke="#131414" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>

          <span>Support@newshack</span>
        </motion.div>
      </div>

      {/* Social Icons */}
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: 0.5 }}
        viewport={{ once: true }}
        className="flex justify-center items-center gap-6 mt-6"
      >
        {[<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.9914 19.4158H4.00863C2.35277 19.4158 1 18.063 1 16.4071V4.4244C1 2.76854 2.35277 1.41577 4.00863 1.41577H15.9914C17.6472 1.41577 19 2.76854 19 4.4244V16.4071C19 18.0704 17.6546 19.4158 15.9914 19.4158Z" stroke="black"/>
<path d="M6.73635 13.6868C7.60863 14.5591 8.7692 15.0396 10.0037 15.0396C11.2382 15.0396 12.3914 14.5591 13.271 13.6868C14.1433 12.8145 14.6238 11.6539 14.6238 10.4194C14.6238 9.18494 14.1433 8.02437 13.271 7.15209C12.3988 6.27981 11.2382 5.79932 10.0037 5.79932C8.7692 5.79932 7.60863 6.27981 6.73635 7.15209C5.86407 8.02437 5.38358 9.18494 5.38358 10.4194C5.38358 11.6539 5.86407 12.8145 6.73635 13.6868Z" stroke="black"/>
<path d="M15.5244 5.64746C16.0143 5.64746 16.4115 5.2503 16.4115 4.76038C16.4115 4.27045 16.0143 3.87329 15.5244 3.87329C15.0344 3.87329 14.6373 4.27045 14.6373 4.76038C14.6373 5.2503 15.0344 5.64746 15.5244 5.64746Z" stroke="black"/>
</svg>
, <svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2 14.0183C3.93756 14.1274 5.63187 13.6744 7.28425 12.475C5.6151 12.1898 4.4576 11.4265 3.82852 9.84963C4.31501 9.78253 4.74278 9.90834 5.24605 9.70704C3.60206 8.9857 2.53681 7.90368 2.49488 6.03323C3.01491 6.07516 3.40914 6.41906 4.02983 6.36873C2.52004 4.8925 2.05871 3.28206 3.00652 1.32773C4.58341 3.13947 6.42871 4.37246 8.685 4.95121C8.81082 4.98476 8.92824 5.01831 9.05405 5.04348C9.6328 5.17768 10.3206 5.46286 10.6729 5.42092C11.2768 5.34543 10.6729 4.64926 10.8239 3.74338C11.302 0.925117 14.4054 -0.358201 16.7036 1.29418C17.3747 1.78066 17.9031 1.77227 18.5657 1.47871C18.9096 1.32773 19.2619 1.17675 19.6729 1.00061C19.5806 1.80583 18.9599 2.25037 18.4651 2.8459C19.027 2.97172 19.48 2.81235 20 2.6446C19.8239 3.22335 19.3877 3.55047 18.9935 3.8692C18.5825 4.19632 18.4231 4.54022 18.4063 5.07703C18.1547 13.2047 8.91986 19.4871 2.60392 14.488C1.99162 14.0015 2.58714 14.488 2 14.0183Z" stroke="black"/>
</svg>
, <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.0679 13.4831H3.93207C2.30826 13.4831 1 12.047 1 10.2807V4.55071C1 2.77716 2.31483 1.34827 3.93207 1.34827H16.0679C17.6917 1.34827 19 2.78434 19 4.55071V10.2807C19.0065 12.0542 17.6917 13.4831 16.0679 13.4831Z" stroke="black"/>
<path d="M13.1839 7.32591L8.07865 4.38196V10.2699L13.1839 7.32591Z" stroke="black"/>
</svg>
, <svg width="20" height="23" viewBox="0 0 20 23" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.2694 1.79244L13.7668 1H10.7254V8.13718L10.715 15.1086C10.7202 15.1604 10.7254 15.2174 10.7254 15.2692C10.7254 17.0146 9.3057 18.439 7.5544 18.439C5.80311 18.439 4.38342 17.0198 4.38342 15.2692C4.38342 13.5237 5.80311 12.0994 7.5544 12.0994C7.9171 12.0994 8.26943 12.1667 8.59586 12.2807V8.80014C8.25907 8.74317 7.91192 8.71209 7.5544 8.71209C3.94301 8.71727 1 11.6592 1 15.2744C1 18.8896 3.943 21.8315 7.55959 21.8315C11.1762 21.8315 14.1192 18.8896 14.1192 15.2744V6.98218C15.4301 8.29256 17.1244 9.57187 19 9.98104V6.42281C16.9637 5.5216 14.9378 2.8594 14.2694 1.79244Z" stroke="black"/>
</svg>
].map((Icon, i) => (
          <motion.a
            key={i}
            whileHover={{ scale: 1.2 }}
            whileTap={{ scale: 0.9 }}
            href="#"
            className="text-gray-700 hover:text-black text-2xl transition"
          >
            {Icon}
          </motion.a>
        ))}
      </motion.div>
    </section>
  );
};


export default ContactSection