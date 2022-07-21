; https://www.codingame.com/training/medium/rubik%C2%AE

(ns Solution (:gen-class))
(defn -main [& args]
  (let [N (read)]
    (println
      (if (> N 1)
        (+ (* (* 6 N) (- N 2)) 8)
        1
      )
    )
  )
)
; (binding [*out* *err*]
;   (println "Debug messages..."))
; Write answer to stdout
