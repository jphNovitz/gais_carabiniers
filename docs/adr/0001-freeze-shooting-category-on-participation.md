# Freeze shooting category on participation

When a member is added to a tir aux plaquettes, the member's usual shooting category is copied onto that participation and may then be adjusted for that tir only. We do this because members can move from tir appuye to tir classique over time, while closed standings must preserve the category that was actually used when the tir happened.

Existing participations are initialized from the member's current usual category during migration. This is an approximation for historical data; important historical corrections are handled manually in the database.
